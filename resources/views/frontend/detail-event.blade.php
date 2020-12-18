@extends('layouts.frontend')
@section('template_title')
    {!! trans('Event Space') !!}
@endsection
@section('content')



<!-- Info section -->
<div id="services" class="service spacer p-t-30 p-b-30">
  <div class="container">
    <div class="row pt-5">
		<div class="col-lg-9">
			<div class="row ">
				<div class="room-img col-lg-6 col-sm-5 col-md-4 mb-sm-7">
					<img src="{{ url('/uploads/'.$eventStarter->image) }}" class="img-fluid" alt="" width="100%">
				 </div>
				  <div class="info col-lg-6 col-sm-7 col-md-8 pt-3 pl-xl-4 pl-lg-5 pl-sm-4">
					<span class="badge badge-secondary">{{$eventStarter->event_category}}</span>
					<h2>{{$eventStarter->event_name}}</h2>
					<small class="text-muted d-block mb-3">Diselenggarakan oleh: <span class="text-muted">{{$eventStarter->organizer}}</span></small>
					<a class="btn btn-small btn-outline-success btn-block" href="">Share This Event</a>
				   </div>
			</div>
		</div>
		<div class="col-lg-3 pt-5">
			<b>10</b>
										<span class="text-muted d-block mb-2">Days to go</span>

									<b>1865 peserta</b>
									<span class="text-muted d-block mb-2">Participants to go</span>

									<p class="mt-3">This event will only be held if it reaches its participant's goal by </p>
									<a class="btn btn-small btn-outline-success" href="">Go</a>
									<a class="btn btn-small btn-outline-success" href="">Cancel</a>
									<a class="btn btn-small btn-outline-success" href="">Re-schedule</a>
		</div>

      </div>
  <!-- Tabs navs -->
<ul class="nav nav-tabs mb-3" id="ex1" role="tablist">
  <li class="nav-item" role="presentation">
    <a
      class="nav-link active"
      id="ex1-tab-1"
      data-toggle="tab"
      href="#ex1-tabs-1"
      role="tab"
      aria-controls="ex1-tabs-1"
      aria-selected="true"
      >Description</a
    >
  </li>
  <li class="nav-item" role="presentation">
    <a
      class="nav-link"
      id="ex1-tab-2"
      data-toggle="tab"
      href="#ex1-tabs-2"
      role="tab"
      aria-controls="ex1-tabs-2"
      aria-selected="false"
      >Discussion</a
    >
  </li>
</ul>
<!-- Tabs navs -->

<!-- Tabs content -->
<div class="tab-content" id="ex1-content">
  <div
    class="tab-pane fade show active"
    id="ex1-tabs-1"
    role="tabpanel"
    aria-labelledby="ex1-tab-1"
  >
    Description
  </div>
  <div class="tab-pane fade" id="ex1-tabs-2" role="tabpanel" aria-labelledby="ex1-tab-2">
    <h6>Anda dapat berdiskusi dan bertanya mengenai Event ini.</h6>

                    @include('frontend.commentDisplay', ['comments' => $eventStarter->comments, 'event_starter_id' => $eventStarter->id])

                    <hr />
                    <h6>Add comment</h6>
                    <form method="post" action="{{ route('post-comment'   ) }}">
                        @csrf
                        <div class="form-group">
						<input type="hidden" name="user_id" value="{{(Auth()->user()->id)}}" />
						<input type="hidden" name="event_starter_id" value="{{ $eventStarter->id }}" />
                            <textarea class="form-control" name="comment"></textarea>

                        </div>
                        <div class="form-group">
                            <input type="submit" class="btn btn-success" value="Add Comment" />
                        </div>
                    </form>
  </div>
</div>
<!-- Tabs content -->
	</div>
</div>
<!-- #info -->

@endsection

@section('footer_scripts')
	<script type="text/javascript">
		$(document).ready(function() {
			$(window).on('scroll', function () {
				if ( $(window).scrollTop() > 10 ) {
					$('.navbar').addClass('active');
				} else {
					$('.navbar').removeClass('active');
				}
			});
		});
  </script>
@endsection
