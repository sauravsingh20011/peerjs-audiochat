<!DOCTYPE html>
<html>

<head>
    <title>PeerJS Sender</title>
    <link rel="stylesheet" href="assets/css/bootstrap.min.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.2/css/all.min.css">
    <script src="peer.min.js"></script>

</head>

<body>
   <div class="container my-5 d-flex justify-content-center align-items-center flex-column" style="min-height:500px;max-width: 300px;background: linear-gradient(45deg, black, white);border-radius:50px">

    <button id="startButton" class="btn btn-secondary" style="background: linear-gradient(45deg, black, white);color:white"><i class="fa-solid fa-phone"></i></button>
    <div class="alert alert-success alert-dismissible fade show" id="alert" role="alert" style="display: none;">
        <strong>Connection Established</strong> 
        <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
    </div>
    <script src="peer.min.js"></script>
    <div class="container mt-5 d-flex justify-content-center " id="loader">
        <div class="spinner-border text-primary" role="status">
            <span class="visually-hidden">Loading...</span>
        </div>
        <div class="spinner-border text-secondary" role="status">
            <span class="visually-hidden">Loading...</span>
        </div>
        <div class="spinner-border text-success" role="status">
            <span class="visually-hidden">Loading...</span>
        </div>
        <div class="spinner-border text-danger" role="status">
            <span class="visually-hidden">Loading...</span>
        </div>
        <div class="spinner-border text-warning" role="status">
            <span class="visually-hidden">Loading...</span>
        </div>
        <div class="spinner-border text-info" role="status">
            <span class="visually-hidden">Loading...</span>
        </div>
        <div class="spinner-border text-light" role="status">
            <span class="visually-hidden">Loading...</span>
        </div>
        <div class="spinner-border text-dark" role="status">
            <span class="visually-hidden">Loading...</span>
        </div>
    </div>
    </div>
    <script>
        let peer = new Peer('2'); // Create a new Peer instance

        peer.on('open', (id) => {
            console.log('My peer ID is: ' + id);

            document.getElementById('startButton').addEventListener('click', () => {
                const receiverID = '1';
                if (receiverID) {
                    const conn = peer.connect(receiverID);

                    conn.on('open', () => {
                        document.getElementById('startButton').style.display = 'none';
                        document.getElementById('loader').style.display = 'none';
                        navigator.mediaDevices.getUserMedia({
                                audio: true
                            })
                            .then((stream) => {
                                const call = peer.call(receiverID, stream);
                                call.on('stream', (remoteStream) => {
                                    const audio = new Audio();
                                    audio.srcObject = remoteStream;
                                    audio.play();
                                });
                            })
                            .catch((error) => {
                                console.error('Error accessing microphone:', error);
                            });
                    });
                }
            });
        });
    </script>
    <script src="assets/js/bootstrap.bundle.min.js"></script>

</body>

</html>