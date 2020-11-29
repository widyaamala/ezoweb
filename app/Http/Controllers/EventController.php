<?php

namespace App\Http\Controllers;

use App\Event;
use App\Product;
use App\Invoice;
use App\Models\User;
use Illuminate\Http\Request;

class EventController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
    		$events = Event::with(['user', 'invoice'])->latest('created_at')->paginate(15);
        return view('pages.events.list', compact('events'));
    }

	public function calendar()
    {
		    $events = Event::all();
        return view('pages.events.calendar', compact('events'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        $data['rooms'] = Product::where('category', 'room')->get();
    		$data['users'] = User::all();
        return view('pages.events.create', $data);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $request->validate([
		        'user_id' => 'required',
            'room_id' => 'required',
		        'payment_method' => 'required',
        ]);

    		$product = Product::find($request->room_id);

    		if ($request->payment_method == 'Cash') {
    			$status = 'Confirmed';
    		} else {
    			$status = 'On Process';
    		}
        //dd($request->user_id);

        // menyimpan data file yang diupload ke variabel $file
    		$file = $request->file('image');

    		$file_name = time()."_".$file->getClientOriginalName();

    		// isi dengan nama folder tempat kemana file diupload
    		$upload_folder = 'receipt';
    		$file->move($upload_folder,$file_name);
        // dd($file_name);
        $event = new Event([
           'user_id' => $request->get('user_id'),
        	 'date'=> $request->get('date'),
           'time' => $request->get('time'),
           'duration'=> $request->get('duration'),
        	 'event_name' => $request->get('event_name'),
        	 'description' => $request->get('description'),
        	 'event_type' => $request->get('event_type'),
        	 'total_seats' => $request->get('total_seats'),
        	 'layout_seat' => $request->get('layout_seat'),
        	 'facilities' => $request->get('facilities'),
           'image'=> $file_name,
        ]);
        $event->save();
        $event->invoice()->create([
           'user_id' => $request->user_id,
           'product_id'=> $product->id,
           'total'=> $product->price,
           'payment_method'=> $request->payment_method,
           'note'=> $request->note,
           'status'=> $status,
        ]);
        $event->status = ($event->invoice->status == 'Confirmed') ? 'Active' : 'Deactive';
        $event->save();

        return redirect('manage/events')->with('success', 'Event has been added');
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Event  $event
     * @return \Illuminate\Http\Response
     */
    public function edit(Event $event)
    {
        $event->load(['user', 'invoice']);
        $data['rooms'] = Product::where('category', 'room')->get();

        return view('pages.events.edit', $data, compact('event'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Event  $event
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Event $event)
    {
        // menyimpan data file yang diupload ke variabel $file
    		$file = $request->file('image');

    		$file_name = time()."_".$file->getClientOriginalName();

    		// isi dengan nama folder tempat kemana file diupload
    		$upload_folder = 'receipt';
    		$file->move($upload_folder,$file_name);

        $data = [
           'user_id' => $request->get('user_id'),
           'date'=> $request->get('date'),
           'time' => $request->get('time'),
           'duration'=> $request->get('duration'),
           'event_name' => $request->get('event_name'),
           'description' => $request->get('description'),
           'event_type' => $request->get('event_type'),
           'total_seats' => $request->get('total_seats'),
           'layout_seat' => $request->get('layout_seat'),
           'facilities' => $request->get('facilities'),
           'image'=> $file_name,
        ];
        $event = $data;
        $event->update();

		return redirect('manage/events')->with('success', 'Event has been updated');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Event  $event
     * @return \Illuminate\Http\Response
     */
    public function destroy(Event $event)
    {
        $event->delete();
         return redirect('manage/events')->with('success', 'Mmebership deleted successfully');
    }
}