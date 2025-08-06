<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Blog;
use App\Models\Product;
use App\Models\Service;
use Illuminate\Http\Request;

class DashboardController extends Controller
{
    public function index()
    {
        $product = Product::query()->get();
        $productLength = $product->count();
        $productPublishedLength = $product->where('published', true)->count();
        $productNotPublishedLength = $product->where('published', false)->count();

        $blog = Blog::query()->get();
        $blogLength = $blog->count();
        $blogPublishedLength = $blog->where('published', true)->count();
        $blogNotPublishedLength = $blog->where('published', false)->count();

        $service = Service::query()->get();
        $serviceLength = $service->count();
        $servicePublishedLength = $service->where('published', true)->count();
        $serviceNotPublishedLength = $service->where('published', false)->count();

        return [
            'data' => [
                'product' => [
                    'title' => "Produits",
                    'total' => $productLength,
                    'published' => $productPublishedLength,
                    'notPublished' => $productNotPublishedLength,
                ],

                'blog' => [
                    'title' => "Blogs",
                    'total' => $blogLength,
                    'published' => $blogPublishedLength,
                    'notPublished' => $blogNotPublishedLength,
                ],

                'service' => [
                    'title' => "Services",
                    'total' => $serviceLength,
                    'published' => $servicePublishedLength,
                    'notPublished' => $serviceNotPublishedLength,
                ],
            ]
        ];
    }
}
