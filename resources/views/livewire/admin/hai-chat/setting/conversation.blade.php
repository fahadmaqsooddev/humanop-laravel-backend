<div class="card setting-box-background mt-4" id="conversation">
    <div class="row h-100">

        <!-- Left-side Navigation Tabs -->
        <div class="col-md-4 col-12 border-dark">
            <ul class="nav nav-tabs flex-column flex-md-row">
                <li class="nav-item">
                    <a class="nav-link active" aria-current="page" href="#"
                       style="font-size: small;">Active</a>
                </li>
                <li class="nav-item">
                    <a class="nav-link" href="#" style="font-size: small;">Archive</a>
                </li>
                <li class="nav-item">
                    <a class="nav-link" href="#" style="font-size: small;">Unread</a>
                </li>
                <li class="nav-item">
                    <a class="nav-link" href="#" style="font-size: small;">Star</a>
                </li>
            </ul>
        </div>


    <!-- Chatbot Conversation Section -->
        <div class="col-md-8 col-12 d-flex flex-column container-fluid"
             style="height: 85vh;">
            @include('layouts.message')

            <div class="p-3" style="font-size: small; color: #0f1534">Assistant</div>
            <div class="d-flex flex-column justify-content-between flex-grow-1 p-3"
                 style="overflow-y: auto;">
                <!-- Message Container -->
                <div id="chatMessages" class="d-flex flex-column gap-3">
                    <!-- Initial Assistant Message -->
                    <div class="d-flex flex-row gap-3 align-items-start">
                        <div class="bg-primary text-white rounded p-2"
                             style="max-width: 70%; font-size:small;">How can I help you?
                        </div>
                    </div>
                    <!-- Initial User Message -->
                    <div class="d-flex flex-row gap-3 justify-content-end">
                        <div class="bg-secondary text-white rounded p-2"
                             style="max-width: 70%; font-size:small;">I need assistance with my
                            account.
                        </div>
                    </div>
                </div>

                <!-- Input Field and Send Button at the bottom -->
                <form wire:submit.prevent="submitForm">
                    <div class="d-flex flex-row gap-3 mt-3" style="width: 100%;">
                        <input type="text" style="flex-grow: 1; background-color: #8bb1ab"
                               class="form-control" id="userInput" name="message" wire:model="message"
                               placeholder="Type your message...">
                        <button style="padding: 10px 16px 10px 16px; border-radius: 7px;" type="submit"
                                class=" mt-4 btn-sm-1 btn-md-3 btn-lg-5 float-end rainbow-border-user-nav-btn navButtonResponsive">
                            send
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>
