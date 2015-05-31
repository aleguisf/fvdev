@extends('master')

@section('head')	

  <link href="{{ asset('css/bootstrap.min.css') }}" rel="stylesheet" type="text/css"/> 
  <link href="{{ asset('css/style.css') }}" rel="stylesheet" type="text/css"/>    

  <style type="text/css">
  		body {
  		  padding-top: 40px;
  		  padding-bottom: 40px;
  		}
      .modal-header {
        border-top-left-radius: 3px;
        border-top-right-radius: 3px;
       }
      .modal-header h4 {
        margin:0;
      }
      .modal-header img {

      }
      .form-signin {
    	  max-width: 400px;
    	  margin: 50px  auto !important;
        background: #fff;
      }
      p.link a {
        font-size: 11px;
      }
      .form-signin .inner {
  	    padding: 20px;
        border-bottom-right-radius: 3px;
        border-bottom-left-radius: 3px;
        border-left: 1px solid #ddd;
        border-right: 1px solid #ddd;
        border-bottom: 1px solid #ddd;
		  }
  		.form-signin .checkbox {
  		  font-weight: normal;
  		}
  		.form-signin .form-control {
  		  margin-bottom: 17px !important;
  		}
  		.form-signin .form-control:focus {
  		  z-index: 2;
  		}
      .titlefv {
        float: right;
      }
		
  </style>

@stop

@section('body')
    <div class="container">

		{{ Former::open('select_branch')->addClass('form-signin') }}
			<div class="modal-header">
                <img style="display:block;margin:0 auto 0 auto;" src="{{ asset('images/icon-login.png') }}" />      

      </div>
      
      <div class="inner">

      <h3 style="text-align: center; margin:0 auto 0 auto; color:#404040;">Seleccione Sucursal</h3>
      
      <br>
			
      <div class="row" style="align:center;">

        <div class="col-lg-12">
        
        {{ Former::select('branch_id')->label('')->data_bind("value: branch_id, valueUpdate: 'afterkeydown'")
              ->fromQuery($branches, 'name', 'public_id')->style('display:inline;width:360px') }}  

    		</div>

      </div>

			<p>{{ Button::success_submit('Continuar', array('class' => 'btn-lg'))->block() }}</p>
		

		{{ Former::close() }}

      </div>
    </div>

@stop