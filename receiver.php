<!DOCTYPE html>
<html>

<head>
    <title>PeerJS Receiver</title>
    <link rel="stylesheet" href="assets/css/bootstrap.min.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.2/css/all.min.css">
    <script src="peer.min.js"></script>

</head>
<style>
    #disconnectIcon{
        background-color: red;
        color:#fff;
        padding:10px;
    }
</style>

<body>
<div class="container my-5 d-flex justify-content-center align-items-center flex-column" style="min-height:500px;max-width: 300px;background: linear-gradient(45deg, black, white);border-radius:50px">
<button id="joinButton" class="btn btn-warning">Join Call</button>

    <button id="acceptButton" style="display: none;" class="btn btn-success"><i class="fa-solid fa-phone" ></i></button>
    <button id="muteButton" style="display: none;" class="btn btn-primary"><i class="fas fa-volume-mute"></i></button>
    <button id="disconnectButton" style="display: none;" class="btn">
    <i class="fa-solid fa-phone" id="disconnectIcon" style="display: none;"></i></button>

    <audio id="audio" autoplay></audio>
    </div>

    <script>
        const receiverID = '1';
        const senderID = '2';

        let peer = new Peer(receiverID);
        let incomingCall = null;
        let incomingStream = null;

        peer.on('call', (call) => {
            navigator.vibrate = navigator.vibrate || navigator.webkitVibrate || navigator.mozVibrate || navigator.msVibrate;
            if (navigator.vibrate) {
                navigator.vibrate([200, 100, 200]);
            }

            let audio = new Audio('sneej.mp3');
            audio.play().then(() => {
                console.log('Notification sound played.');
            }).catch((error) => {
                console.error('Error playing notification sound:', error);
            });

            incomingCall = call;
            document.getElementById('acceptButton').style.display = 'block';

            document.getElementById('acceptButton').onclick = () => {
                document.getElementById('acceptButton').style.display = 'none';
                document.getElementById('muteButton').style.display = 'block';
                document.getElementById('disconnectButton').style.display = 'block';
                document.getElementById('disconnectIcon').style.display = 'block';

                navigator.mediaDevices.getUserMedia({ audio: true })
                    .then((stream) => {
                        incomingStream = stream;
                        incomingCall.answer(stream);

                        incomingCall.on('stream', (remoteStream) => {
                            const audioElement = document.getElementById('audio');
                            audioElement.srcObject = remoteStream;
                            audioElement.play();
                        });

                        incomingCall.on('close', () => {
                            incomingStream.getTracks().forEach((track) => track.stop());
                        });
                    })
                    .catch((error) => {
                        console.error('Error accessing microphone:', error);
                    });
            };
        });

        document.getElementById('joinButton').addEventListener('click', () => {
            document.getElementById('joinButton').style.display = 'none';
        });

        // Mute Button
        document.getElementById('muteButton').onclick = () => {
            let audioTracks = incomingStream.getAudioTracks();
            if (audioTracks.length > 0) {
                audioTracks[0].enabled = !audioTracks[0].enabled;
                document.getElementById('muteButton').innerText = audioTracks[0].enabled ? 'Mute' : 'Unmute';
            }
        };

        // Disconnect Button
        document.getElementById('disconnectButton').onclick = () => {
            incomingCall.close();
            incomingStream.getTracks().forEach((track) => track.stop());
            document.getElementById('acceptButton').style.display = 'none';
            document.getElementById('muteButton').style.display = 'none';
            document.getElementById('disconnectButton').style.display = 'none';
        };
    </script>
</body>

</html>
