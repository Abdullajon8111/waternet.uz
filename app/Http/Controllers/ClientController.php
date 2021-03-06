<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Client;
use App\Models\ClientPrices;
use App\Models\ClientContainer;
use App\Models\UserOrganization;
use App\Models\Organization;
use App\Models\Order;
use App\Models\TakeProduct;
use App\Models\EntryProduct;
use App\Models\TakeContainer;
use App\Models\EntryContainer;
use App\Models\Product;
use App\Models\User;
use App\Models\Sity;
use App\Models\Area;
use App\Models\Sms;

use Auth;

class ClientController extends Controller
{
    public function view_location($id)
    {
        $client = Client::find($id);
        $locations[0] = $client->fullname;

        $arr = explode(',',$client->location);

        $locations[1] = $arr[0];
        $locations[2] = $arr[1];
        $locations[3] = 1;

        return view('clients.viewlocation',[
            'locations' => $locations
        ]);
    }

    public function client_price(Request $request, $id)
    {
        $clientprice = new ClientPrices();
        $clientprice->organization_id = UserOrganization::where('user_id',Auth::user()->id)->value('organization_id');
        $clientprice->client_id = $id;
        $clientprice->user_id = Auth::user()->id;
        $clientprice->payment = $request->payment;
        $clientprice->amount = $request->amount;
        $clientprice->comment = $request->comment ?? ''; 
        $clientprice->save();

        $client = Client::find($id);
        $client->balance = $client->balance + $request->amount;
        $client->save();

       
        return redirect()->back()->with('msg' ,'success');
    }

    public function client_price_edit(Request $request, $id)
    {
        $clientprice = ClientPrices::find($id);
        $price = $clientprice->amount;
        $clientprice->payment = $request->payment;
        $clientprice->amount = $request->amount;
        $clientprice->comment = $request->comment ?? ''; 
        $clientprice->save();

        $client = Client::find($clientprice->client_id);
        $client->balance = $client->balance - $price +  $request->amount;
        $client->save();

       
        return redirect()->back()->with('msg' ,'success');
    }

    public function client_price_delete( $id)
    {
        $clientprice = ClientPrices::find($id);
        $price = $clientprice->amount;

        $client = Client::find($clientprice->client_id);
        $client->balance = $client->balance - $price;
        $client->save();
        $clientprice->delete();
       
        return redirect()->back()->with('msg' ,'success');
    }

    public function client_container(Request $request, $id)
    {
        $clientprice = new ClientContainer();
        $clientprice->organization_id = UserOrganization::where('user_id',Auth::user()->id)->value('organization_id');
        $clientprice->client_id = $id;
        $clientprice->user_id = Auth::user()->id;
        $clientprice->product_id = $request->product_id;
        $clientprice->count = $request->count;
        $clientprice->invalid_count = $request->invalid_count; 
        $clientprice->comment = $request->comment ?? ''; 
        $clientprice->save();

        $client = Client::find($id);
        $client->container = $client->container + $request->count - $request->invalid_count;
        $client->save();

       
        return redirect()->back()->with('msg' ,'success');
    }

    public function client_container_edit(Request $request, $id)
    {
        $clientprice = ClientContainer::find($id);
        $count = $clientprice->count;
        $incount = $clientprice->invalid_count;

        $clientprice->product_id = $request->product_id;
        $clientprice->count = $request->count;
        $clientprice->invalid_count = $request->invalid_count; 
        $clientprice->comment = $request->comment ?? ''; 
        $clientprice->save();

        $client = Client::find($clientprice->client_id);
        $client->container = $client->container - ($count-$incount) + $request->count - $request->invalid_count;
        $client->save();

        return redirect()->back()->with('msg' ,'success');
    }
    public function client_container_delete($id)
    {
        $clientprice = ClientContainer::find($id);
        $count = $clientprice->count;
        $incount = $clientprice->invalid_count;

        $client = Client::find($clientprice->client_id);
        $client->container = $client->container - ($count-$incount);
        $client->save();

        $clientprice->delete();
       
        return redirect()->back()->with('msg' ,'success');
    }

    public function success_order_view($id)
    {  
        $order = Order::find($id);
        $summ = $order->product_count * $order->price;
        $info_id = UserOrganization::where('user_id',Auth::user()->id)->value('organization_id');
        $info_org = Organization::find($info_id);

        return view('clients.success_order',[
            'order' => $order,
            'summ' => $summ,
            'info_org' => $info_org
        ]);
    }

    public function take_products()
    {  
        $takeproduct = TakeProduct::where('organization_id',UserOrganization::where('user_id',Auth::user()->id)->value('organization_id'))->with('received')->with('sent')->with('product')->get();

        $products = Product::where('organization_id', UserOrganization::where('user_id',Auth::user()->id)->value('organization_id'))->get();
        $organ = UserOrganization::where('user_id',Auth::user()->id)->value('organization_id');
    
        $arr = UserOrganization::where('organization_id', $organ)->pluck('user_id')->toArray();
       
        $users = User::whereIn('id',$arr)->get();
        $info_id = UserOrganization::where('user_id',Auth::user()->id)->value('organization_id');
        $info_org = Organization::find($info_id);


        return view('sklad.take-product',[
            'takeproduct' => $takeproduct,
            'products' => $products,
            'users' => $users,
            'info_org' => $info_org
        ]);
    }

    public function entry_products()
    {  
        $entryproduct = EntryProduct::where('organization_id',UserOrganization::where('user_id',Auth::user()->id)->value('organization_id'))->with('user')->with('product')->get();

        $products = Product::where('organization_id', UserOrganization::where('user_id',Auth::user()->id)->value('organization_id'))->get();
        $info_id = UserOrganization::where('user_id',Auth::user()->id)->value('organization_id');
        $info_org = Organization::find($info_id);

        return view('sklad.entry-product',[
            'entryproduct' => $entryproduct,
            'products' => $products,
            'info_org' => $info_org
        ]);
    }

    public function entry_container()
    {  
        $entrycontainer = EntryContainer::where('organization_id',UserOrganization::where('user_id',Auth::user()->id)->value('organization_id'))
        ->with('user')
        ->with('received')
        ->with('product')->get();

        $products = Product::where('organization_id', UserOrganization::where('user_id',Auth::user()->id)->value('organization_id'))->get();
        $organ = UserOrganization::where('user_id',Auth::user()->id)->value('organization_id');
    
        $arr = UserOrganization::where('organization_id', $organ)->pluck('user_id')->toArray();
       
        $users = User::whereIn('id',$arr)->get();
        $info_id = UserOrganization::where('user_id',Auth::user()->id)->value('organization_id');
        $info_org = Organization::find($info_id);

        return view('sklad.entry-container',[
            'entrycontainer' => $entrycontainer,
            'products' => $products,
            'users' => $users,
            'info_org' => $info_org
        ]);
    }

    public function take_container()
    {  
        $takecontainer = TakeContainer::where('organization_id',UserOrganization::where('user_id',Auth::user()->id)->value('organization_id'))
        ->with('user')
        ->with('product')->get();

        $products = Product::where('organization_id', UserOrganization::where('user_id',Auth::user()->id)->value('organization_id'))->get();
        $info_id = UserOrganization::where('user_id',Auth::user()->id)->value('organization_id');
        $info_org = Organization::find($info_id);

        return view('sklad.take-container',[
            'takecontainer' => $takecontainer,
            'products' => $products,
            'info_org' => $info_org
        ]);
    }

    public function add_take_product(Request $request)
    {  
        $organ = UserOrganization::where('user_id',Auth::user()->id)->value('organization_id');

        $takproduct = new TakeProduct();
        $takproduct->organization_id = $organ;
        $takproduct->received_id = $request->user_id;
        $takproduct->sent_id = Auth::user()->id;
        $takproduct->product_id = $request->product_id;
        $takproduct->product_count = $request->product_count;
        $takproduct->save();

        $prod = Product::find($request->product_id);
        $prod->product_count = $prod->product_count - $request->product_count;
        $prod->save();
        
        return redirect()->back()->with('msg' ,'success');
    }

    public function take_edit_product(Request $request, $id)
    {  
        $takproduct = TakeProduct::find($id);
        $takproduct->received_id = $request->user_id;
        $takproduct->sent_id = Auth::user()->id;
        $takproduct->product_id = $request->product_id;
        $old = $takproduct->product_count;
        $takproduct->product_count = $request->product_count;
        $takproduct->save();

        $prod = Product::find($request->product_id);
        $prod->product_count = $prod->product_count + $old - $request->product_count;
        $prod->save();
        
        return redirect()->back()->with('msg' ,'success');
    }

    public function take_delete_product(Request $request, $id)
    {  
        $takproduct = TakeProduct::find($id);
        $old = $takproduct->product_count;
        $oldid = $takproduct->product_id;

        $prod = Product::find($oldid);
        $prod->product_count = $prod->product_count + $old;
        $prod->save();
        $takproduct->delete();
        
        return redirect()->back()->with('msg' ,'success');
    }

    public function add_entry_product(Request $request)
    {  
        $organ = UserOrganization::where('user_id',Auth::user()->id)->value('organization_id');

        $entryproduct = new EntryProduct();
        $entryproduct->organization_id = $organ;
        $entryproduct->product_id = $request->product_id;
        $entryproduct->user_id = Auth::user()->id;
        $entryproduct->product_count = $request->product_count;
        $entryproduct->price = $request->price;
        $entryproduct->comment = $request->comment?? '';
        $entryproduct->save();

        $prod = Product::find($request->product_id);
        $prod->product_count = $prod->product_count + $request->product_count;
        $prod->save();
        
        return redirect()->back()->with('msg' ,'success');
    }

    public function edit_entry_product(Request $request, $id)
    {  
        $entryproduct = EntryProduct::find($id);

        $entryproduct->product_id = $request->product_id;
        $entryproduct->user_id = Auth::user()->id;
        $old = $entryproduct->product_count;
        $entryproduct->product_count = $request->product_count;
        $entryproduct->price = $request->price;
        $entryproduct->comment = $request->comment?? '';
        $entryproduct->save();

        $prod = Product::find($request->product_id);
        $prod->product_count = $prod->product_count - $old + $request->product_count;
        $prod->save();
        
        return redirect()->back()->with('msg' ,'success');
    }

    public function delete_entry_product($id)
    {  
        $entryproduct = EntryProduct::find($id);
        $old = $entryproduct->product_count;
        $oldid = $entryproduct->product_id;

        $entryproduct->save();

        $prod = Product::find($oldid);
        $prod->product_count = $prod->product_count - $old;
        $prod->save();
        
        return redirect()->back()->with('msg' ,'success');
    }

    public function add_entry_container(Request $request)
    {  
        //dd($request->all());
        $organ = UserOrganization::where('user_id',Auth::user()->id)->value('organization_id');

        $entrycontainer = new EntryContainer();
        $entrycontainer->organization_id = $organ;
        $entrycontainer->product_id = $request->product_id;
        $entrycontainer->user_id = $request->user_id;
        $entrycontainer->product_count = $request->container_count;
        $entrycontainer->received_id = Auth::user()->id;
        $entrycontainer->save();

        $prod = Product::find($request->product_id);
        $prod->container = $prod->container + $request->container_count;
        $prod->save();
        
        return redirect()->back()->with('msg' ,'success');
    }

    public function edit_entry_container(Request $request, $id)
    {  
        
        $entrycontainer = EntryContainer::find($id);
        $old = $entrycontainer->product_count;

        $entrycontainer->product_id = $request->product_id;
        $entrycontainer->user_id = $request->user_id;
        $entrycontainer->product_count = $request->container_count;
        $entrycontainer->received_id = Auth::user()->id;
        $entrycontainer->save();

        $prod = Product::find($request->product_id);
        $prod->container = $prod->container - $old + $request->container_count;
        $prod->save();
        
        return redirect()->back()->with('msg' ,'success');
    }

    public function delete_entry_container($id)
    {  
        
        $entrycontainer = EntryContainer::find($id);
        $prodid = $entrycontainer->product_id;
        $old = $entrycontainer->product_count;

        $prod = Product::find($prodid);
        $prod->container = $prod->container - $old;
        $prod->save();     
        $entrycontainer->delete();
        
        return redirect()->back()->with('msg' ,'success');
    }

    public function edit_product(Request $request, $id)
    {  
        $product = Product::find($id);
        $product->name = $request->name;
        $product->product_count = $request->product_count;
        $product->container_status = $request->container_status;
        $product->container = $request->container;
        $product->save();
        
        return redirect()->back()->with('msg' ,'success');
    }

    public function delete_product($id)
    {  
        $product = Product::find($id)->delete();
        
        return redirect()->back()->with('msg' ,'success');
    }
    
    public function take_entry_container(Request $request)
    {  
       // dd($request->all());
        $organ = UserOrganization::where('user_id',Auth::user()->id)->value('organization_id');

        $entrycontainer = new TakeContainer();
        $entrycontainer->organization_id = $organ;
        $entrycontainer->user_id = Auth::user()->id;
        $entrycontainer->product_id = $request->product_id;
        $entrycontainer->product_count = $request->product_count;
        $entrycontainer->comment = $request->comment?? '';
        $entrycontainer->save();

        $prod = Product::find($request->product_id);
        $prod->container = $prod->container - $request->product_count;
        $prod->save();
        
        return redirect()->back()->with('msg' ,'success');
    }

    public function take_edit_container(Request $request, $id)
    {  
        $entrycontainer = TakeContainer::find($id);
        $entrycontainer->user_id = Auth::user()->id;
        $entrycontainer->product_id = $request->product_id;
        $old = $entrycontainer->product_count;

        $entrycontainer->product_count = $request->product_count;
        $entrycontainer->comment = $request->comment?? '';
        $entrycontainer->save();

        $prod = Product::find($request->product_id);
        $prod->container = $prod->container - $request->product_count + $old;
        $prod->save();
        
        return redirect()->back()->with('msg' ,'success');
    }

    public function take_delete_container($id)
    {  
        $entrycontainer = TakeContainer::find($id);
        $old = $entrycontainer->product_count;
        $oldid = $entrycontainer->product_id;

        $prod = Product::find($oldid);
        $prod->container = $prod->container + $old;
        $prod->save();
        $entrycontainer->delete();
        
        return redirect()->back()->with('msg' ,'success');
    }

    public function send_message()
    {  
        $clients = Client::where('organization_id',UserOrganization::where('user_id',Auth::user()->id)
        ->value('organization_id'))->with('city')
        ->with('user')
        ->with('area');

        $sities = Sity::where('organization_id',UserOrganization::where('user_id',Auth::user()->id)->value('organization_id'))->get();
        $areas = Area::where('organization_id', UserOrganization::where('user_id',Auth::user()->id)->value('organization_id'))->get();
        $products = Product::where('organization_id', UserOrganization::where('user_id',Auth::user()->id)->value('organization_id'))->get();
        $info_id = UserOrganization::where('user_id',Auth::user()->id)->value('organization_id');
        $info_org = Organization::find($info_id);

        return view('smsmanager.sendmessage',[
            'clients' => $clients->paginate(10),
            'sities' => $sities,
            'areas' => $areas,
            'products' => $products,
            'info_org' => $info_org
        ]);
    }

    public function sms_text()
    {  
        $info_id = UserOrganization::where('user_id',Auth::user()->id)->value('organization_id');
        $info_org = Organization::find($info_id);

        return view('smsmanager.smstext',[
            'info_org' => $info_org
        ]);
    }

    public function success_message()
    {  
        $smsmanagers = Sms::where('organization_id',UserOrganization::where('user_id',Auth::user()->id)
        ->value('organization_id'))
        ->with('client')
        ->with('user')
        ->get();
        $info_id = UserOrganization::where('user_id',Auth::user()->id)->value('organization_id');
        $info_org = Organization::find($info_id);

        return view('smsmanager.successmessage',[
            'smsmanagers' => $smsmanagers,
            'info_org' => $info_org
        ]);
    }


    public function send_client_message($text, $phone_number, $id)
    {  
        
        $char = ['(', ')', ' ','-','+'];
        $replace = ['', '', '','',''];
        $phone = str_replace($char, $replace, $phone_number);

        $curl = curl_init();

        curl_setopt_array($curl, array(
          CURLOPT_URL => 'http://sms.etc.uz:8084/json2sms',
          CURLOPT_RETURNTRANSFER => true,
          CURLOPT_ENCODING => '',
          CURLOPT_MAXREDIRS => 10,
          CURLOPT_TIMEOUT => 0,
          CURLOPT_FOLLOWLOCATION => true,
          CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
          CURLOPT_CUSTOMREQUEST => 'POST',
          CURLOPT_POSTFIELDS =>"{
                   \"login\":\"sms0085ts\",
                   \"pwd\":\"1986@max\",
                   \"CgPN\":\"WEBEST_UZ\",
                   \"CdPN\":\"998$phone\",
                   \"text\":\"$text\"
               }",
          CURLOPT_HTTPHEADER => array(
            'Accept: application/json',
            'Content-Type: application/json'
          ),
        ));
        
        $response = curl_exec($curl);
       
        $json = json_decode($response, true);
        
        if ($json['query_state'] == "SUCCESS") {         

            $organ = UserOrganization::where('user_id',Auth::user()->id)->value('organization_id');
            $count = Organization::find($organ);
            $count->sms_count = $count->sms_count + 1;
            $count->save();

            $sms = new Sms();
            $sms->organization_id = $organ;
            $sms->client_id = $id;
            $sms->user_id = Auth::user()->id;
            $sms->sms_text = $text;
            $sms->save();
        }
        
        return 1;
    }
    
    public function send_sms(Request $request)
    {
        $organ = UserOrganization::where('user_id',Auth::user()->id)->value('organization_id');
        $count = Organization::find($organ);

        if($count->sms_count < $count->traffic->sms_count) {
            
            $arr = $request->checkbox;
            $x = 0;
    
            foreach ($arr as $key => $value) {  
                $phone_number = Client::find($key)->phone;
    
                if ( $this->send_client_message($request->text, $phone_number, $key) == 1 ) $x++;
            }
    
            return redirect()->back()->with('msg' , $x);
        } else  return redirect()->back()->with('msg' , 0);

       
    }

    public function client_profile(Request $request)
    {
        //dd($request->all());

        $error = '
        {
            "error" : "Unauthorized"
        }
        ';

        $client = Client::where('login',$request->login)->where('password',$request->password)->get();

        if(count($client)) return  $client;
        else
            return json_decode($error, true); 
    }
}
