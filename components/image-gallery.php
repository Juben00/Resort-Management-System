<?php
function renderImageGallery($images)
{
    if (empty($images)) {
        return;
    }
    ?>
    <div class="relative">
        <!-- Main Image Container with Grid of 4 Images -->
        <div class="grid grid-cols-4 gap-2 h-[500px]">
            <!-- Large Main Image -->
            <div class="col-span-2 row-span-2 relative overflow-hidden">
                <img id="mainImage" src="<?php echo $images[0]; ?>" alt="Venue"
                    class="w-full h-full object-cover transition-all duration-300">
            </div>

            <!-- Smaller Images -->
            <?php for ($i = 1; $i < min(5, count($images)); $i++): ?>
                <div class="overflow-hidden">
                    <img src="<?php echo $images[$i]; ?>" alt="Venue view <?php echo $i + 1; ?>"
                        class="w-full h-full object-cover cursor-pointer thumbnail"
                        onclick="updateMainImage('<?php echo $images[$i]; ?>')">
                </div>
            <?php endfor; ?>
        </div>

        <!-- Show All Photos Button -->
        <button id="showAllPhotos" class="absolute bottom-5 right-5 bg-white px-4 py-2 rounded-lg shadow-md 
                   flex items-center gap-2 hover:bg-gray-50 transition-colors">
            <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor"
                class="w-5 h-5">
                <path stroke-linecap="round" stroke-linejoin="round"
                    d="M2.25 15.75l5.159-5.159a2.25 2.25 0 013.182 0l5.159 5.159m-1.5-1.5l1.409-1.409a2.25 2.25 0 013.182 0l2.909 2.909m-18 3.75h16.5a1.5 1.5 0 001.5-1.5V6a1.5 1.5 0 00-1.5-1.5H3.75A1.5 1.5 0 002.25 6v12a1.5 1.5 0 001.5 1.5zm10.5-11.25h.008v.008h-.008V8.25zm.375 0a.375.375 0 11-.75 0 .375.375 0 01.75 0z" />
            </svg>
            Show all photos
        </button>

        <!-- Full Screen Gallery Modal -->
        <div id="galleryModal" class="fixed inset-0 bg-black z-50 hidden">
            <div class="h-full flex flex-col">
                <!-- Modal Header -->
                <div class="p-4 flex justify-between items-center bg-black text-white">
                    <button id="closeGallery" class="hover:opacity-70 p-2">
                        <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="2.5"
                            stroke="currentColor" class="w-6 h-6">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M6 18L18 6M6 6l12 12" />
                        </svg>
                    </button>
                    <span class="text-sm"><?php echo count($images); ?> photos</span>
                    <div class="w-6"></div> <!-- Spacer for centering -->
                </div>

                <!-- Modal Content -->
                <div class="flex-1 flex flex-col items-center justify-center px-4 py-2">
                    <!-- Main Modal Image -->
                    <div class="relative w-full h-[calc(100vh-250px)] flex items-center justify-center">
                        <img id="modalMainImage" src="<?php echo $images[0]; ?>" alt="Main gallery image"
                            class="max-h-full max-w-full object-contain">

                        <!-- Navigation Arrows -->
                        <button id="prevImage"
                            class="absolute left-4 p-2 rounded-full bg-black/50 text-white hover:bg-black/70">
                            <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="2.5"
                                stroke="currentColor" class="w-6 h-6">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M15 19l-7-7 7-7" />
                            </svg>
                        </button>
                        <button id="nextImage"
                            class="absolute right-4 p-2 rounded-full bg-black/50 text-white hover:bg-black/70">
                            <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="2.5"
                                stroke="currentColor" class="w-6 h-6">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M9 5l7 7-7 7" />
                            </svg>
                        </button>
                    </div>

                    <!-- Thumbnails Strip -->
                    <div class="w-full mt-4 px-4">
                        <div class="flex gap-2 overflow-x-auto py-2">
                            <?php foreach ($images as $index => $image): ?>
                                <img src="<?php echo $image; ?>" alt="Thumbnail"
                                    class="modal-thumbnail h-20 w-32 object-cover cursor-pointer hover:opacity-80"
                                    onclick="updateModalImage(<?php echo $index; ?>)">
                            <?php endforeach; ?>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <script>
        document.addEventListener('DOMContentLoaded', function () {
            const mainImage = document.getElementById('mainImage');
            const modalMainImage = document.getElementById('modalMainImage');
            const showAllPhotos = document.getElementById('showAllPhotos');
            const galleryModal = document.getElementById('galleryModal');
            const closeGallery = document.getElementById('closeGallery');
            const prevButton = document.getElementById('prevImage');
            const nextButton = document.getElementById('nextImage');
            let currentImageIndex = 0;
            const totalImages = <?php echo count($images); ?>;
            const images = <?php echo json_encode($images); ?>;

            // Function to update main image with transition
            window.updateMainImage = function (newSrc) {
                mainImage.style.opacity = '0';
                setTimeout(() => {
                    mainImage.src = newSrc;
                    mainImage.style.opacity = '1';
                }, 300);
            };

            // Function to update modal image (no transition)
            window.updateModalImage = function (index) {
                currentImageIndex = index;
                modalMainImage.src = images[index];

                // Update thumbnail highlighting
                document.querySelectorAll('.modal-thumbnail').forEach((thumb, i) => {
                    if (i === index) {
                        thumb.classList.add('ring-2', 'ring-white');
                    } else {
                        thumb.classList.remove('ring-2', 'ring-white');
                    }
                });
            };

            // Show gallery modal
            if (showAllPhotos) {
                showAllPhotos.addEventListener('click', function (e) {
                    e.preventDefault();
                    if (galleryModal) {
                        galleryModal.classList.remove('hidden');
                        document.body.style.overflow = 'hidden';
                        updateModalImage(currentImageIndex);
                    }
                });
            }

            // Navigation buttons
            prevButton.addEventListener('click', () => {
                const newIndex = (currentImageIndex - 1 + totalImages) % totalImages;
                updateModalImage(newIndex);
            });

            nextButton.addEventListener('click', () => {
                const newIndex = (currentImageIndex + 1) % totalImages;
                updateModalImage(newIndex);
            });

            // Keyboard navigation
            document.addEventListener('keydown', function (e) {
                if (galleryModal.classList.contains('hidden')) return;

                switch (e.key) {
                    case 'ArrowLeft':
                        prevButton.click();
                        break;
                    case 'ArrowRight':
                        nextButton.click();
                        break;
                    case 'Escape':
                        closeGallery.click();
                        break;
                }
            });

            // Close gallery modal
            if (closeGallery) {
                closeGallery.addEventListener('click', function (e) {
                    e.preventDefault();
                    if (galleryModal) {
                        galleryModal.classList.add('hidden');
                        document.body.style.overflow = 'auto';
                    }
                });
            }
        });
    </script>
    <?php
}
?>