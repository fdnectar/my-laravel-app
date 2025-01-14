<?php

namespace App\Http\Controllers;

use App\Models\Order;
use App\Models\Product;
use App\Models\ProductImage;
use Illuminate\Http\Request;
use App\Rules\ProductPriceRule;
use Illuminate\Support\Facades\DB;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\File;
use App\Services\SkuGeneratorService;

class ProductController extends Controller
{
    protected $skuGenerator;
    protected $inventoryService;

    public function __construct(SkuGeneratorService $skuGenerator)
    {
        $this->skuGenerator = $skuGenerator;
    }

    public function viewProdut(Request $request) {
        $data = [
            'pageTitle' => 'All Products',
            'products' => Product::all()
        ];

        return view('back.pages.product', $data);
    }

    public function addProduct(Request $request) {
        $data = [
            'pageTitle' => 'Add Product',
        ];
        return view('back.pages.add-product', $data);
    }

    public function storeProduct(Request $request) {
        $request->validate([
            'product_name' => 'required|unique:products,product_name',
            'product_description' => 'required|min:100',
            'product_image' => 'required|mimes:jpg,jpeg,png|max:1024',
            'product_price' => ['required', new ProductPriceRule()],
            'sku_prefix' => 'nullable|string|max:10',
        ], [
            'product_name.required' => 'Enter product name',
            'product_name.unique' => 'This product name is already taken',
            'product_description.required' => 'Write summary for this product',
            'product_image.required' => 'Choose image for this product',
            'product_price.required' => 'Enter product price'
        ]);

        $sku = $request->sku ?? $this->skuGenerator->generateSku(
            $request->product_name,
            $request->sku_prefix
        );

        $product_image = null;
        if($request->hasFile('product_image')) {
            $path = 'images/products/';
            $file = $request->file('product_image');
            $filename = 'PIMG_'.time().uniqid().'.'.$file->getClientOriginalExtension();
            $upload = $file->move(public_path($path), $filename);

            if($upload) {
                $product_image = $filename;
            }
        }

        $product = new Product();
        $product->user_id = auth()->id();
        $product->sku = $sku;
        $product->product_name = $request->product_name;
        $product->description = $request->product_description;
        $product->product_price = $request->product_price;
        $product->product_image = $product_image;
        $saved = $product->save();
        if($saved) {
            return response()->json(['status' => 1, 'msg' => 'Product Added Successfully']);
        } else {
            return response()->json(['status' => 0, 'msg' => 'Something went wrong']);
        }
    }

    public function editProduct(Request $request) {
        $product_id = $request->id;
        $product = Product::findOrFail($product_id);

        $data = [
            'pageTitle' => 'Edit Product',
            'product' => $product
        ];

        return view('back.pages.edit-product', $data);
    }

    public function updateProduct(Request $request) {
        $product_id = $request->product_id;
        $product = Product::findOrFail($product_id);
        $product_image = $product->product_image;

        $request->validate([
            'product_name' => 'required|unique:products,product_name,'.$product->id,
            'product_description' => 'required|min:100',
            'product_image' => 'nullable|mimes:jpg,jpeg,png|max:1024',
            'product_price' => ['required', new ProductPriceRule()],
            'sku_prefix' => 'nullable|string|max:10',
        ], [
            'product_name.required' => 'Enter product name',
            'product_name.unique' => 'This product name is already taken',
            'product_description.required' => 'Write summary for this product',
            'product_price.required' => 'Enter product price'
        ]);

        $sku = $request->sku ?? $this->skuGenerator->generateSku(
            $request->product_name,
            $request->sku_prefix
        );

        if($request->hasFile('product_image')) {
            $path = 'images/products/';
            $file = $request->file('product_image');
            $filename = 'PIMG_'.time().uniqid().'.'.$file->getClientOriginalExtension();
            $old_product_image = $product->product_image;

            $upload = $file->move(public_path($path), $filename);


            if($upload) {
                if(File::exists(public_path($path.$old_product_image))) {
                    File::delete(public_path($path.$old_product_image));
                }

                $product_image = $filename;
            }
        }

        $product->product_name = $request->product_name;
        $product->sku = $sku;
        $product->description = $request->product_description;
        $product->product_price = $request->product_price;
        $product->product_image = $product_image;
        $product->stock_quantity = $request->stock_quantity;
        $product->low_stock_threshold = $request->low_stock_threshold;

        if ($request->stock_quantity <= 0) {
            $product->status = 'out_of_stock';
        } else if ($request->stock_quantity <= $request->low_stock_threshold) {
            $product->status = 'low_stock';
        } else {
            $product->status = 'active';
        }

        $product->product_image = $product_image;
        $updated = $product->save();

        if($updated) {
            return response()->json(['status' => 1, 'msg' => 'Product Updated Successfully']);
        } else {
            return response()->json(['status' => 0, 'msg' => 'Something went wrong']);
        }
    }

    public function uploadProductImages(Request $request) {
        $product = Product::findOrFail($request->product_id);
        $path = 'images/products/additionals/';
        $file = $request->file('file');
        $filename = 'APIMG_'.$product->id.time().uniqid().'.'.$file->getClientOriginalExtension();

        $file->move(public_path($path), $filename);

        $pimage = new ProductImage();
        $pimage->product_id = $product->id;
        $pimage->image = $filename;
        $pimage->save();
    }

    public function getProductImages(Request $request) {
        $product = Product::with('images')->findOrFail($request->product_id);
        $path = "images/products/additionals/";
        $html = "";
        if($product->images->count() > 0) {
            foreach($product->images as $item) {
                $html.='<div class="box">
                            <img src="/'.$path.$item->image.'" alt="">
                            <a href="javascript:;" data-image="'.$item->id.'" class="btn btn-danger btn-sm" id="deleteProductImage">
                                <i class="mdi mdi-delete"></i>
                            </a>
                        </div>';
            }
        } else {
            $html = '<span class="text-danger">No image(s)</span>';
        }
        return response()->json(['status'=>1, 'data'=>$html]);
    }

    public function deleteProductImages(Request $request) {
        $product_image = ProductImage::findOrFail($request->image_id);
        $path = "images/products/additionals/";

        if($product_image->image != null && File::exists(public_path($path.$product_image->image))) {
            File::delete(public_path($path.$product_image->image));
        }

        $delete = $product_image->delete();
        if($delete) {
            return response()->json(['status'=>1, 'msg'=>'Product image deleted successfully']);
        } else {
            return response()->json(['status'=>0, 'msg'=>'Something went wrong']);
        }
    }

    public function deleteProduct(Request $request) {
        $product = Product::with('images')->findOrFail($request->product_id);

        //Delete additional image
        if($product->images->count() > 1) {
            $images_path = 'images/products/additionals/';
            foreach($product->images as $item) {
                if($item->image != null && File::exists(public_path($images_path.$item->image))) {
                    File::delete(public_path($images_path.$item->image));
                }
                $pimage = ProductImage::findOrFail($item->id);
                $pimage->delete();
            }
        }

        $path = 'images/products/';
        $product_image = $product->product_image;
        if($product_image != null && File::exists(public_path($path.$product_image))) {
            File::delete(public_path($path.$product_image));
        }
        $delete = $product->delete();
        if($delete) {
            return response()->json(['status'=>1, 'msg'=>'Product deleted successfully']);
        } else {
            return response()->json(['status'=>0, 'msg'=>'Something went wrong']);
        }
    }

    public function showCartItems(Request $request) {
        $data = [
            'pageTitle' => 'All Cart Items',
            'carts' => ShoppingCart::orderBy('created_at', 'desc')->get()
        ];

        return view('back.pages.cart-items', $data);
    }

    public function showOrderItems(Request $request) {
        $data = [
            'pageTitle' => 'All Order Items',
        ];

        return view('back.pages.cart-items', $data);
    }
}
