<?php

class BranchController extends \BaseController {

  public function getDatatable()
  {
    $query = DB::table('branches')
                ->where('branches.account_id', '=', Auth::user()->account_id)
                ->where('branches.deleted_at', '=', null)
                ->where('branches.public_id', '>', 0)
                ->select('branches.public_id', 'branches.name', 'branches.economic_activity', 'branches.address1', 'branches.address2', 'branches.work_phone');


    return Datatable::query($query)
      ->addColumn('name', function($model) { return link_to('branches/' . $model->public_id . '/edit', $model->name); })
      ->addColumn('economic_activity', function($model) { return nl2br(Str::limit($model->economic_activity, 100)); })
      ->addColumn('address1', function($model) { return nl2br(Str::limit($model->address2, 60)).', '.nl2br(Str::limit($model->address1, 40)); })
      ->addColumn('work_phone', function($model) { return nl2br(Str::limit($model->work_phone, 30)); })
      ->addColumn('dropdown', function($model) 
      { 
        return '<div class="btn-group tr-action" style="visibility:hidden;">
            <button type="button" class="btn btn-xs btn-default dropdown-toggle" data-toggle="dropdown">
              '.trans('texts.select').' <span class="caret"></span>
            </button>
            <ul class="dropdown-menu" role="menu">
            <li><a href="' . URL::to('branches/'.$model->public_id) . '/edit">'.uctrans('texts.edit_branch').'</a></li>                
            <li class="divider"></li>
            <li><a href="' . URL::to('branches/'.$model->public_id) . '/archive">'.uctrans('texts.archive_branch').'</a></li>
          </ul>
        </div>';
      })       
      ->orderColumns(['name', 'address1'])
      ->make();           
  }

  public function edit($publicId)
  {
    $branch = Branch::scope($publicId)->firstOrFail();

    $data = [
      'showBreadcrumbs' => false,
      'branch' => $branch,
      'third' => $branch->third,
      'method' => 'PUT', 
      'aux' => 'yes',
      'url' => 'branches/' . $publicId, 
      'title' => trans('texts.edit_branch')
    ];

    $data = array_merge($data, self::getViewModel());     
    return View::make('accounts.branch', $data);   
  }

  public function create()
  {
    $data = [
      'showBreadcrumbs' => false,
      'branch' => null,
      'third' => null,
      'method' => 'POST',
      'aux' => 'no',
      'url' => 'branches', 
      'title' => trans('texts.create_branch')
    ];

    $data = array_merge($data, self::getViewModel()); 
    return View::make('accounts.branch', $data);       
  }

  private static function getViewModel()
  {
    return [   

      'branch_types' => BranchType::remember(DEFAULT_QUERY_CACHE)->orderBy('name')->get(),      
    ];
  }

  public function store()
  {
    return $this->save();
  }

  public function update($publicId)
  {
    return $this->save($publicId);
  }  

  private function save($branchPublicId = false)
  {

    // $error = false;
    // $var1 = 'NÚMERO DE TRAMITE';
    // $var2 = 'NÚMERO DE AUTORIZACIÓN';

    // $number_tramit = trim(Input::get('number_process'));
    // $number_autho = trim(Input::get('number_autho'));

    // if(Input::file('dosage'))
    // {
    //   $file = Input::file('dosage');
    //   $name = $file->getRealPath();
    //   $i = 0;
    //   $file = fopen($name, "r");
    //   while(!feof($file))
    //   {
    //     $process1 = fgets($file);
    //     if($i =='0')
    //     {
    //       $process2 = explode(":", $process1);
    //       $result1 = $process2[0];
    //       if(strcmp($result1, $var1) !== 0){$error=1;}
    //       $result1 = trim($process2[1]);
    //       if(strcmp($result1, $number_tramit) !== 0){$error=1;}
    //     }
    //     if($i =='2')
    //     {
    //       $process2 = explode(":", $process1);
    //       $result2 = $process2[0];
    //       if(strcmp($result2, $var2) !== 0){$error=1;}
    //       $result1 = trim($process2[1]);
    //       if(strcmp($result1, $number_autho) !== 0){$error=1;}
    //     }
    //     $i++;
    //   }
    //   fclose($file);
    // }

		// if ($error ==1) 
		// {
  //         Session::flash('error', 'Arhivo inválido');
		// 		  $url = $branchPublicId ? 'branches/' . $branchPublicId . '/edit' : 'branches/create';
  //         return Redirect::to('company/branches');
		// } 
		// else 
		// {

		    if ($branchPublicId)
		    {
		      $branch = Branch::scope($branchPublicId)->firstOrFail();
		    }
		    else
		    {
		      $branch = Branch::createNew();
		    }

		    $branch->name = trim(Input::get('branch_name'));
        $branch->branch_type_id = trim(Input::get('branch_type_id'));

		    $branch->address2 = trim(Input::get('address2'));
        $branch->address1 = trim(Input::get('address1'));
        $branch->work_phone = trim(Input::get('work_phone'));
		    $branch->city = trim(Input::get('city'));
		    $branch->state = trim(Input::get('state'));

        $branch->deadline = Input::get('deadline');
        
        $branch->key_dosage = trim(Input::get('dosage'));

        // if(Input::file('dosage'))
        // {
        //   $file = Input::file('dosage');
        //   $name = $file->getRealPath();
        
        //   $i = 0;
        //   $file = fopen($name, "r");
        //   while(!feof($file))
        //   {
        //     $process1 = fgets($file);
        //     if($i =='0')
        //     {
        //       $process2 = explode(":", $process1);
        //       $result1 = $process2[1];
        //     }
        //     if($i =='2')
        //     {
        //       $process2 = explode(":", $process1);
        //       $result2 = $process2[1];
        //     }
        //     if($i =='6')
        //     {
        //       $result3 = $process1;
        //     }
        //     $i++;
        //   }
        //   fclose($file);

          // $branch->number_process = trim($result1);
          // $branch->number_autho = trim($result2);
          // $branch->key_dosage = trim($result3);
        // }

		    $branch->economic_activity = trim(Input::get('economic_activity'));

        $branch->number_process = trim(Input::get('number_process'));
        $branch->number_autho = trim(Input::get('number_autho'));
        $branch->key_dosage = trim(Input::get('key_dosage'));   
           
	      $branch->law = trim(Input::get('law'));
        $branch->type_third = trim(Input::get('third_view'));
        $branch->invoice_number_counter = 1;
		    $branch->save();

        $account = Auth::user()->account;
        $account->op2 = true;
        $account->save();

		    $message = $branchPublicId ? trans('texts.updated_branch') : trans('texts.created_branch');
        
        Session::flash('message', $message);

		    return Redirect::to('company/branches');    
		// }
  }

  public function archive($publicId)
  {
    $branch = Branch::scope($publicId)->firstOrFail();
    $branch->delete();

    Session::flash('message', trans('texts.archived_branch'));
    return Redirect::to('company/branches');        
  }

}