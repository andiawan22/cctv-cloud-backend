import sys
import time
from onvif import ONVIFCamera

if len(sys.argv) < 6:
    print("Penggunaan: python3 ptz_control.py <ip> <user> <pass> <arah> <port>")
    sys.exit(1)

ip = sys.argv[1]
user = sys.argv[2]
password = sys.argv[3]
arah = sys.argv[4].lower()
port = int(sys.argv[5])

try:
    mycam = ONVIFCamera(ip, port, user, password)
    media = mycam.create_media_service()
    ptz = mycam.create_ptz_service()

    profiles = media.GetProfiles()
    token = profiles[0].token

    x = 0.0
    y = 0.0
    
    if arah == "left": x = -0.5
    elif arah == "right": x = 0.5
    elif arah == "up": y = 0.5
    elif arah == "down": y = -0.5
    elif arah == "stop":
        ptz.Stop({'ProfileToken': token})
        sys.exit(0)

    request = {
        'ProfileToken': token,
        'Velocity': {'PanTilt': {'x': x, 'y': y}}
    }

    ptz.ContinuousMove(request)
    time.sleep(0.5) 
    ptz.Stop({'ProfileToken': token})
    
    print(f"Sukses bergerak ke {arah} lewat port {port}")

except Exception as e:
    print(f"Error ONVIF: {e}")
