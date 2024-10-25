@extends('user_type.auth', ['parentFolder' => 'dashboards', 'childFolder' => 'none'])
<style>
    .modal-close-btn {
        background: #f2661c;
        border: none;
        color: white;
        font-weight: bold;
        font-size: x-large;
        float: right;
        border-radius: 3px;
        padding: 0px 10px 1px 10px;
    }

    input::placeholder {
        color: white !important;
    }
</style>
@section('content')
    <div class="container-fluid mt-4">
        <div class="d-flex justify-content-end">
            <a data-bs-toggle="modal" data-bs-target="#createChatModal"
               style="padding: 10px 16px 10px 16px; border-radius: 7px;"
               class="btn-sm-2 btn-md-3 btn-lg-5 rainbow-border-user-nav-btn navButtonResponsive">Create chat
            </a>
        </div>


        <div id="noChatbotsMessage" class="text-center mt-3" style="color: #0f1534">
            You don’t have any chatbots yet.
        </div>


        <!-- Chatbot Cards Container -->
        <div id="chatbotCardsContainer" class="mt-3 row p-3">
            <!-- Example Card -->
            <div class="card mt-3 col-md-6" style="padding-right: 5px">
                <div class="card-body">
                    <div class="d-flex flex-column gap-3">
                        <a href="./Pages/chatdetails.html">
                            <div class="text-white text-decoration-none"><i class="bi bi-robot"></i> Brain</div>
                            <p class="card-text text-white">This is an example description</p>
                        </a>
                        <div class="d-flex justify-content-between">
                            <p class="text-white" style="padding-right: 8px"><i class="bi bi-clock text-white"></i> less
                                than a minute</p>
                            <div class="d-flex gap-2">
                                <button style="padding: 10px 16px 10px 16px; border-radius: 7px;"
                                        onclick="copyCard(this)"
                                        class="btn-sm-2 btn-md-3 btn-lg-5 rainbow-border-user-nav-btn navButtonResponsive">
                                    <i class="fa-solid fa-copy"></i></button>
                                <button style="padding: 10px 16px 10px 16px; border-radius: 7px;"
                                        onclick="confirmDelete(this)"
                                        class="btn-sm-2 btn-md-3 btn-lg-5 rainbow-border-user-nav-btn navButtonResponsive">
                                    <i class="fa-solid fa-trash"></i></button>
                            </div>
                        </div>
                    </div>


                </div>
                </a>
            </div>
        </div>

        <!-- Create Chat Modal -->
        <div class="modal fade" id="createChatModal" tabindex="-1" aria-labelledby="createChatModalLabel"
             aria-hidden="true">
            <div class="modal-dialog modal-lg">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title text-white" id="createChatModalLabel">Create New Chat</h5>
                        <button type="button" class="close modal-close-btn" data-bs-dismiss="modal"
                                aria-label="Close">
                            <span aria-hidden="true">&times;</span>
                        </button>
                    </div>
                    <div class="modal-body">
                        <div class="mb-3">
                            <input type="text" class="form-control text-white" style="background-color: #1c365e"
                                   id="chatName" placeholder="Enter chat name">
                        </div>
                        <div class="mb-3">
                            <textarea class="form-control" id="chatDescription" style="background-color: #1c365e; color: white" rows="3"
                                      placeholder="Enter chat description"></textarea>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="submit" class="btn btn-sm float-end mt-4 mb-4 text-white" data-bs-dismiss="modal"
                                onclick="createChatCard()" style="background-color: #f2661c">Generate
                        </button>
                    </div>
                </div>
            </div>
        </div>

        <!-- Confirm Delete Modal -->
        <div class="modal fade" id="confirmDeleteModal" tabindex="-1" aria-labelledby="confirmDeleteModalLabel"
             aria-hidden="true">
            <div class="modal-dialog modal-dialog-centered">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title text-white" id="confirmDeleteModalLabel">Confirm Delete</h5>
                        <button type="button" class="close modal-close-btn" data-bs-dismiss="modal"
                                aria-label="Close">
                            <span aria-hidden="true">&times;</span>
                        </button>
                    </div>
                    <div class="modal-body">
                        Are you sure you want to delete this chatbot?
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                        <button type="button" class="btn btn-danger" id="confirmDeleteBtn" data-bs-dismiss="modal"
                                onclick="deleteCard()">Delete
                        </button>
                    </div>
                </div>
            </div>
        </div>
    @endsection

    @push('js')
        <!-- Bootstrap JS and Icons -->
            {{--    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/js/bootstrap.bundle.min.js"></script>--}}

            <script>
                let cardToDelete = null;

                // Function to create a new card based on modal inputs
                function createChatCard() {
                    const name = document.getElementById('chatName').value;
                    const description = document.getElementById('chatDescription').value;

                    if (!name || !description) return; // Skip if fields are empty

                    // Create chatbot object
                    const chatbot = {
                        name: name,
                        description: description,
                    };

                    // Get existing chatbots from local storage
                    const chatbots = JSON.parse(localStorage.getItem('chatbots')) || [];
                    chatbots.push(chatbot); // Add new chatbot to the array
                    localStorage.setItem('chatbots', JSON.stringify(chatbots)); // Save updated array to local storage


                    const cardHtml = `
            <div class="card mt-3 col-md-6" >
                <div class="card-body">
                    <div class="d-flex flex-column gap-3">
                                                <a href="./Pages/chatdetails.html">
                        <div class="text-white text-decoration-none"><i class="bi bi-robot"></i> ${name}</div>
                        <p class="card-text text-white">${description}</p>
                                                </a>
                        <div class="d-flex justify-content-between">
                        <p class="text-white" style="padding-right: 8px"><i class="bi bi-clock text-white"></i> just now</p>
                              <div class="d-flex gap-2">
<button style="padding: 10px 16px 10px 16px; border-radius: 7px;"
                                        onclick="copyCard(this)"
                                        class="btn-sm-2 btn-md-3 btn-lg-5 rainbow-border-user-nav-btn navButtonResponsive">
                                    <i class="fa-solid fa-copy"></i></button>
                                <button style="padding: 10px 16px 10px 16px; border-radius: 7px;"
                                        onclick="confirmDelete(this)"
                                        class="btn-sm-2 btn-md-3 btn-lg-5 rainbow-border-user-nav-btn navButtonResponsive">
                                    <i class="fa-solid fa-trash"></i></button>
                    </div>
                        </div>
                    </div>

                </div>
            </div>`;

                    document.getElementById('chatbotCardsContainer').insertAdjacentHTML('beforeend', cardHtml);
                }

                // Function to clone a card and append (copy) to the name
                function copyCard(button) {
                    const card = button.closest('.card');
                    const clonedCard = card.cloneNode(true);
                    const title = clonedCard.querySelector('.text-white').textContent.trim();

                    // Append "(copy)" to the cloned card's title
                    clonedCard.querySelector('.text-white').innerHTML = `<i class="bi bi-robot"></i> ${title} (copy)`;

                    document.getElementById('chatbotCardsContainer').appendChild(clonedCard);
                }

                // Function to confirm deletion
                function confirmDelete(button) {
                    cardToDelete = button.closest('.card');
                    const confirmDeleteModal = new bootstrap.Modal(document.getElementById('confirmDeleteModal'));
                    confirmDeleteModal.show();
                }

                // Function to delete the card after confirmation
                function deleteCard() {
                    if (cardToDelete) {
                        cardToDelete.remove();
                        cardToDelete = null;
                    }
                    // Check if any cards are left; show no-chatbots message if none

                    toggleNoChatbotsMessage();

                }

                // Function to toggle the no-chatbots message
                function toggleNoChatbotsMessage() {
                    const noChatbotsMessage = document.getElementById('noChatbotsMessage');
                    const chatbotCardsContainer = document.getElementById('chatbotCardsContainer');

                    if (chatbotCardsContainer.children.length === 0) {
                        noChatbotsMessage.style.display = 'block';
                    } else {
                        noChatbotsMessage.style.display = 'none';
                    }
                }


                // Initialize: Show the no-chatbots message if no cards exist initially
                toggleNoChatbotsMessage();
            </script>
    @endpush
