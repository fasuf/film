@extends('templates.index')

@section('content')
<div class="faq">
    <div class="container">
        <div class="agileits-single-top" style="margin-bottom: 25px;">
            <ol class="breadcrumb">
                <li><a href="{{ route('home') }}">Home</a></li>
                <li class="active">FAQ</li>
            </ol>
        </div>

        <div class="tittle-head" style="margin-bottom: 30px;">
            <h4 class="latest-text w3_faq_latest_text">Frequently Asked Questions</h4>
            <p style="color: #777; margin-top: 10px; font-size: 15px;">
                Pertanyaan yang sering diajukan mengenai fitur, katalog film, dan panduan penggunaan platform OneMovies.
            </p>
        </div>

        <div class="panel-group w3_panel" id="accordion" role="tablist" aria-multiselectable="true">
            @foreach ($faqs as $index => $item)
                <div class="panel panel-default" style="margin-bottom: 15px; border: 1px solid #e5e5e5; border-radius: 4px; overflow: hidden; box-shadow: 0 1px 3px rgba(0,0,0,0.04);">
                    <div class="panel-heading" role="tab" id="heading{{ $index }}" style="padding: 18px 20px; background-color: #fcfcfc;">
                        <h4 class="panel-title asd" style="font-size: 16px; margin: 0;">
                            <a class="pa_italic {{ $index > 0 ? 'collapsed' : '' }}" role="button" data-toggle="collapse" data-parent="#accordion" href="#collapse{{ $index }}" aria-expanded="{{ $index === 0 ? 'true' : 'false' }}" aria-controls="collapse{{ $index }}" style="display: block; color: #212121; text-decoration: none; font-weight: 600;">
                                <span class="glyphicon glyphicon-plus" aria-hidden="true" style="margin-right: 8px; color: #ff8d1b;"></span><i class="glyphicon glyphicon-minus" aria-hidden="true" style="margin-right: 8px; color: #ff8d1b;"></i>
                                <label style="cursor: pointer; margin-bottom: 0; font-weight: 600; color: #212121;">{{ $index + 1 }}. {{ $item['question'] }}</label>
                            </a>
                        </h4>
                    </div>
                    <div id="collapse{{ $index }}" class="panel-collapse collapse {{ $index === 0 ? 'in' : '' }}" role="tabpanel" aria-labelledby="heading{{ $index }}">
                        <div class="panel-body panel_text" style="padding: 20px 25px; font-size: 14.5px; line-height: 1.8; color: #555; background: #ffffff; border-top: 1px solid #f0f0f0;">
                            {{ $item['answer'] }}
                        </div>
                    </div>
                </div>
            @endforeach
        </div>
    </div>
</div>
@endsection
