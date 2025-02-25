@push('css')
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/chosen/1.8.7/chosen.min.css" integrity="sha512-yVvxUQV0QESBt1SyZbNJMAwyKvFTLMyXSyBHDO4BG5t7k/Lw34tyqlSDlKIrIENIzCl+RVUNjmCPG+V/GMesRw==" crossorigin="anonymous" referrerpolicy="no-referrer" />
  <style>
      #chatDots {
          margin: 32px;
      }
      .chatDot {
          width: 10px;
          height: 10px;
          background-color: #f2661c;
          display: inline-block;
          margin: 1px;
          border-radius: 50%;
      }

      .chatDot:nth-child(1) {
          animation: bounce 1s infinite;
      }

      .chatDot:nth-child(2) {
          animation: bounce 1s infinite .2s;
      }

      .chatDot:nth-child(3) {
          animation: bounce 1s infinite .4s;
      }
      @keyframes bounce {
          0% {
              transform: translateY(0px);
          }

          50% {
              transform: translateY(8px);
          }

          100% {
              transform: translateY(0px);
          }
      }

      .like,
      .dislike {
          display: inline-block;
          cursor: pointer;
          margin: 10px;
          color: lightgray;
      }

      .dislike:hover,
      .like:hover {
          color: #f2661c;
          transition: all .2s ease-in-out;
          transform: scale(1.1);
      }

      .active {
          color: #f2661c;
      }

      .disabledCard {
          pointer-events: none;
          opacity: 0.4;
      }
      </style>
    @endpush
 
    
    {{-- {{dd($selectedModels)}} --}}
    <div wire:ignore.self class="modal fade" id="ComparisonModal" tabindex="-1" aria-labelledby="ComparisonModal"
    aria-hidden="true">
   <div class="modal-dialog modal-lg">
       <div class="modal-content">
           <div class="modal-body">
               <div class="d-flex justify-content-between " style="margin-bottom:1rem;">
                   <h5 class="modal-title text-white" id="createChatModalLabel">Comparison Model</h5>
                  
               
                   <button type="button" class="close modal-close-btn new-orange-button" id="ComparisonModal"
                           data-bs-dismiss="modal"
                           aria-label="Close">
                       <span aria-hidden="true">&times;</span>
                       
                   </button>
                   
               </div>
               <div class="row d-flex justify-content-end align-items-center">
                <div class="col-md-6 ">
                    @if ($val < $maxVal)
                    <button wire:click="addMore" class="btn btn-primary mt-3" style="background-color:#f2661c; color: white; border-radius: 8px; padding: 10px 20px;">
                        Add
                    </button>
                @endif
                </div>
                <div class="col-md-6 ">
                    {{-- <div class="d-flex justify-content-end" style="margin-right: 24px;margin-top: 18px" >
                        <div class="d-flex justify-content-end w-50" wire:ignore>
                            <label for="user_name"></label>
                            <select name="user_name" wire:model="user_id" class="form-control chzn-select" id="user_name" style="background-color: #F3DEB4;color: #000000;border-radius:20px">
                                <option value="">Select User</option>
                                @if(isset($user_details))
                                 @foreach($user_details as $user_detail)
                                    <option value="{{$user_detail['id']}}">{{$user_detail['first_name'] .' '. $user_detail['last_name']}}</option>
                                 @endforeach
                                @endif
                            </select>
                        </div>
                    </div> --}}
                    <div class="d-flex justify-content-end" style="margin-right: 24px;margin-top: 18px;margin-bottom:10px;" >
                    <select name="" id="" class="form-control " style="background-color: #F3DEB4;color: #000000;border-radius:0px">
                        <option value="">Select User</option>
                        @if(isset($user_details))
                         @foreach($user_details as $user_detail)
                            <option value="{{$user_detail['id']}}">{{$user_detail['first_name'] .' '. $user_detail['last_name']}}</option>
                         @endforeach
                        @endif
                    </select>
                </div>
                    <div class="row">
                        <div class="col-6">
                        </div>
                    </div>
                </div>
               </div>
             
               @include('layouts.message')
         
              

               <div class="row">
                   @foreach(array_slice($modelTypes, 0, $val) as $model)  
                   <div class="col-md-6" style="">  
                       <div class="card bg-dark text-white shadow-sm p-3 mb-3" style="height: 200px;">
                        <span style="margin-left: 27px;">Select LLM Model</span>
                           <div class="card-body">
                               <div class="d-flex align-items-center justify-content-between">
                                
                                   <h5 class="card-title m-0 " style="color: white">{{ $model['model_name'] }}</h5>
                                   <input type="checkbox" name="selectedModels" wire:model="selectedModels" value="{{ $model['model_name'] }}" class="form-check-input">
                                  
                               </div>
                             
                           </div>
                       </div>
                   </div>
                   @endforeach
               </div>


               <div class="row">
               

                <form wire:submit.prevent="" id="">
                    <div class="text-center">
                        {{-- @if(session('admin_conversation'))
                            <span class="text-success">{{session('admin_conversation')}}</span>
                        @endif --}}
                    </div>
                    <div class="d-flex justify-content-between" style="margin-left: 4px;margin-right: 0px;margin-bottom: 14px">
                        <div style="width: 100%">
                            <input type="text" wire:loading.attr="disabled" wire:target="user_id" id="" wire:model.defer="" placeholder="Your message....." class="form-control" style="padding: 4px;border-radius: 20px;padding-left: 10px;padding-right: 10px">
                        </div>
                        <div style="width: 5%" class="pt-1">
                            <button class="bg-transparent" type="submit" style="border:none" id="">
                                <img src="{{asset('assets\img\icons\mynaui_send-solid.png')}}"  width="25" height="25" >
                            </button>
                        </div>
                    </div>
                </form>
               </div>
               
               


           </div>
       </div>
   </div>
</div>
               
@push('javascript')
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/chosen/1.8.7/chosen.jquery.min.js" integrity="sha512-rMGGF4wg1R73ehtnxXBt5mbUfN9JUJwbk21KMlnLZDJh7BkPmeovBuddZCENJddHYYMkCh9hPFnPmS9sspki8g==" crossorigin="anonymous" referrerpolicy="no-referrer"></script>
   
@endpush

@push('js')

 

@endpush

