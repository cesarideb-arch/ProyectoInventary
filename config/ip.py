import socket

def get_local_ip():
    try:
        # Crear un socket y conectar a una dirección IP pública
        s = socket.socket(socket.AF_INET, socket.SOCK_DGRAM)
        s.settimeout(0)
        s.connect(('8.8.8.8', 80))
        ip = s.getsockname()[0]
        s.close()
        return ip
    except Exception as e:
        print(f"Error getting local IP: {e}")
        return None

def update_env_file(ip):
    env_file = '.env'  # Ruta a tu archivo .env de Laravel
    try:
        with open(env_file, 'r') as file:
            lines = file.readlines()
        
        with open(env_file, 'w') as file:
            for line in lines:
                if line.startswith('BACKEND_API'):
                    file.write(f'BACKEND_API="http://apiinventary.idebmexico.com"\n')
                else:
                    file.write(line)
        print(f"Archivo .env actualizado con la IP: {ip}")
    except Exception as e:
        print(f"Error al actualizar el archivo .env: {e}")

if __name__ == "__main__":
    ip = get_local_ip()
    if ip:
        update_env_file(ip)
