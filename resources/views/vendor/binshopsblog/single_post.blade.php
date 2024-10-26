@extends('layouts.app', ['page_route' => 'blog'])

@section('title', 'Welcome')

@section('content')
    
    
    
    <!-- =======================
Main Content START -->
    <section class="pb-0 pt-4 pb-md-5" style="margin-top:100px;">
        <div class="container">
            <div class="row">
                <div class="col-12">
                    
                    
                    @if(config("binshopsblog.reading_progress_bar"))
                        <div id="scrollbar">
                            <div id="scrollbar-bg"></div>
                        </div>
                    @endif
                    
                    {{--https://github.com/binshops/laravel-blog--}}
                    
                    <div class='container'>
                        <div class='row'>
                            <div class='col-sm-12 col-md-12 col-lg-12'>
                                @include("binshopsblog::partials.show_errors")
                                </div>
                        </div>
                    </div>
                    
                    
                    <!-- Title and Info START -->
                    <div class="row">
                        <!-- Content -->
                        <div class="col-lg-12 order-1">
                            <!-- Pre title -->
                            <span>{{$post->post->posted_at->diffForHumans()}}</span><span class="mx-2">|</span>
                            
                            @foreach($categories as $category)
                                <div class="badge text-bg-success">
                                    {{$category->categoryTranslations[0]->category_name}}
                                </div>
                            @endforeach
                            <!-- Title -->
                            <h1 class="mt-2 mb-0">{{$post->title}}</h1>
                            <h5 class="mt-2 mb-0">{{$post->subtitle}}</h5>
                            <!-- Info -->
                            <p class="mt-2">{!! $post->short_description !!}</p>
                            
                            <p class="mb-0"><?=$post->image_tag("medium", false, 'd-block mx-auto'); ?></p>
                        
                        </div>
                    </div>
                    <!-- Title and Info END -->
                    
                    
                    <!-- Quote and content START -->
                    <div class="row mt-4">
                        <!-- Content -->
                        <div class="col-12 mt-4 mt-lg-0">
                            <p><!-- span class="dropcap h6 mb-0 px-2">S</span -->{!! $post->post_body !!}</p>
                            <!-- List -->
                        </div>
                    </div>
                    <!-- Quote and content END -->
                    
                    
                    
                    
                    <hr> <!-- Divider -->
                </div>
            </div> <!-- Row END -->
        </div>
    </section>
    <!-- =======================
		Main Content END -->
    
    <script src="{{asset('binshops-blog.js')}}"></script>
    
    
    @include('layouts.footer')

@endsection



@push('scripts')
    <script>
        $(document).ready(function () {
        });
    </script>

@endpush


