<!-- resources/views/upload.blade.php -->
<!DOCTYPE html>
<html>
<head>
    <title>Take Photo and Upload</title>
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <style>
        #video {
            width: 320px;
            height: 240px;
        }
        #canvas {
            display: none;
        }
    </style>
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            const video = document.querySelector('#video');
            const canvas = document.querySelector('#canvas');
            const captureButton = document.querySelector('#capture');
            const uploadButton = document.querySelector('#upload');

            // Access the user's webcam
            navigator.mediaDevices.getUserMedia({ video: true })
                .then(stream => {
                    video.srcObject = stream;
                })
                .catch(err => {
                    console.error('Error accessing webcam: ', err);
                });

            // Capture the image from the video stream
            captureButton.addEventListener('click', function() {
                const context = canvas.getContext('2d');
                canvas.width = video.videoWidth;
                canvas.height = video.videoHeight;
                context.drawImage(video, 0, 0, canvas.width, canvas.height);

                // Convert the image to base64
                const base64String = canvas.toDataURL('image/png').replace('data:image/png;base64,', '');
                document.getElementById('base64Image').value = base64String;
            });

            // Upload the image to the server
            uploadButton.addEventListener('click', async function(event) {
                event.preventDefault();
                const base64Image = document.getElementById('base64Image').value;

                const response = await fetch('/upload-image', {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                        'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content'),
                    },
                    body: JSON.stringify({ image: base64Image }),
                });

                const result = await response.json();
                console.log(result);
            });
        });
    </script>
</head>
<body>
    <h1>Take Photo and Upload</h1>
    <video id="video" autoplay></video>
    <button id="capture">Capture Photo</button>
    <canvas id="canvas"></canvas>
    <input type="hidden" id="base64Image" name="base64Image">
    <button id="upload">Upload Image</button>
</body>
</html>
