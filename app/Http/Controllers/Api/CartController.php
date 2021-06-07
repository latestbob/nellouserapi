<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Cart;
use App\Models\PharmacyDrug;
use App\Traits\FileUpload;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Str;
use Ramsey\Uuid\Uuid;

class CartController extends Controller
{

    use FileUpload;

    public function getItems(Request $request) {

        $validator = Validator::make($request->all(), [
            'cart_uuid' => 'required|uuid'
        ]);

        if ($validator->fails()) {
            return response([
                'status' => false,
                'message' => $validator->errors()
            ]);
        }

        return Cart::with(['drug', 'drug.category'])->where(['cart_uuid' => $request->cart_uuid])->get();
    }

    public function addToCart(Request $request)
    {

        $validator = Validator::make($request->all(), [
            'drug_id' => 'required|integer',
            'quantity' => 'nullable|integer'
        ]);

        if ($validator->fails()) {
            return response([
                'status' => false,
                'message' => $validator->errors()
            ]);
        }

        $data = $validator->validated();

        $drug = PharmacyDrug::where(['id' => $data['drug_id']])->first();

        if (empty($drug)) {
            return response()->json([
                'status' => false,
                'message' => 'Failed to add to cart. Drug not found'
            ]);
        }

        if (!isset($data['quantity'])) {
            $data['quantity'] = 1;
        }

        if (empty($request->cart_uuid) || !Uuid::isValid($request->cart_uuid)) {
            $request->cart_uuid = strtolower(Str::uuid()->toString());
        }

        if ($data['quantity'] == 0) return $this->removeFromCart($request);

        $data['price'] = $drug->price * $data['quantity'];

        $data['vendor_id'] = $drug->vendor_id;

        $data['cart_uuid'] = $request->cart_uuid;

        $data['user_id'] = Auth::check() ? $request->user()->id : 0;

        $cart = Cart::with(['drug'])->where(['drug_id' => $data['drug_id'], 'cart_uuid' => $data['cart_uuid']])->first();

        if (empty($cart)) {
            $cart = Cart::create($data);
            $cart->load('drug');
        } else {
            $cart->quantity = $data['quantity'];
            $cart->price = $data['price'];
            $cart->save();
        }

        return response()->json([
            'status' => true,
            'message' => 'Added to cart successfully',
            'cart_uuid' => $request->cart_uuid,
            'cart' => $cart
        ]);
    }

    public function updateCart(Request $request)
    {

        $validator = Validator::make($request->all(), [
            'cart_uuid' => 'required|uuid',
            'drug_id' => 'required|integer',
            'quantity'  => 'required|integer'
        ]);

        if ($validator->fails()) {
            return response([
                'status' => false,
                'message' => $validator->errors()
            ]);
        }

        $data = $validator->validated();

        $cart_uuid = $request->cart_uuid;
        $drug = PharmacyDrug::where(['id' => $data['drug_id']])->first();

        if (empty($drug)) {
            return response()->json([
                'status' => false,
                'message' => 'Failed to update cart. Drug not found'
            ]);
        }

        $cart = Cart::where(['drug_id' => $data['drug_id'], 'cart_uuid' => $cart_uuid])->first();

        if (empty($cart)) {
            return $this->addToCart($request);

            /*return response()->json([
                'status' => false,
                'message' => 'Failed to update cart. Item not found in cart'
            ]);*/
        }

        $cart->quantity = $request->quantity;

        if ($cart->quantity < 1) return $this->removeFromCart($request);

        $cart->price = $drug->price * $cart->quantity;

        $cart->save();

        return response()->json([
            'status' => true,
            'message' => 'Cart updated successfully',
            'cart_uuid' => $request->cart_uuid,
            'cart' => $cart
        ]);
    }

    public function removeFromCart(Request $request)
    {

        $validator = Validator::make($request->all(), [
            'cart_uuid' => 'required|uuid',
            'drug_id' => 'required|integer',
        ]);

        if ($validator->fails()) {
            return response([
                'status' => false,
                'message' => $validator->errors()
            ]);
        }

        Cart::where([
            'cart_uuid' => $request->cart_uuid,
            'drug_id'   => $request->drug_id
        ])->delete();

        return [
            'status' => true,
            'message' => 'Removed'
        ];
    }

    public function addPrescription(Request $request) {

        $validator = Validator::make($request->all(), [
            'cart_uuid' => 'required|uuid',
            'drug_id' => 'required|integer',
            'file' => 'required|file|mimes:jpeg,jpg,png,pdf',
        ]);

        if ($validator->fails()) {
            return response([
                'status' => false,
                'message' => $validator->errors()
            ]);
        }

        $item = Cart::where(['cart_uuid' => $request->cart_uuid, 'drug_id' => $request->drug_id])->first();

        if (empty($item)) {

            return response(['status' => false, 'message' => [["Failed to add prescription, item not found"]]]);
        }

        if ($request->hasFile('file')) {

            $item->prescription = $prescription = $this->uploadFile($request, 'file');
            $item->prescribed_by = 'customer';
            $item->save();

            return response(['status' => true, 'message' => "Prescription uploaded and added successfully", 'prescription' => $prescription]);

        } else return response(['status' => false, 'message' => [["No prescription file uploaded"]]]);
    }
}
