import os
import struct
import zlib

def create_png(width, height, filename):
    # Pure Python PNG creator for black/cyan fuel icon
    pixels = []
    cx, cy = width / 2, height / 2
    r_outer = width * 0.42
    
    for y in range(height):
        row = []
        for x in range(width):
            dx = x - cx
            dy = y - cy
            dist = (dx*dx + dy*dy)**0.5
            
            # Dark obsidian background #09090b
            red, green, blue, alpha = 9, 9, 11, 255
            
            # Outer ring gradient cyan #06b6d4 to emerald #10b981
            if width * 0.35 <= dist <= r_outer:
                # Gradient factor
                t = (x / width)
                red = int(6 * (1 - t) + 16 * t)
                green = int(182 * (1 - t) + 185 * t)
                blue = int(212 * (1 - t) + 129 * t)
            
            # Fuel pump symbol in center (approx shape)
            if abs(dx) < width * 0.12 and abs(dy) < height * 0.18:
                red, green, blue = 244, 244, 245
                
            row.extend([red, green, blue, alpha])
        pixels.append(bytes(row))

    os.makedirs(os.path.dirname(filename), exist_ok=True)
    
    # PNG binary structure
    raw_data = b"".join(b"\x00" + row for row in pixels)
    compressed = zlib.compress(raw_data)
    
    def chunk(tag, data):
        return struct.pack(">I", len(data)) + tag + data + struct.pack(">I", zlib.crc32(tag + data) & 0xffffffff)

    ihdr = struct.pack(">IIBBBBB", width, height, 8, 6, 0, 0, 0)
    
    with open(filename, "wb") as f:
        f.write(b"\x89PNG\r\n\x1a\n")
        f.write(chunk(b"IHDR", ihdr))
        f.write(chunk(b"IDAT", compressed))
        f.write(chunk(b"IEND", b""))

create_png(192, 192, "/Users/red/Desktop/my-popo/public/icons/icon-192.png")
create_png(512, 512, "/Users/red/Desktop/my-popo/public/icons/icon-512.png")
create_png(180, 180, "/Users/red/Desktop/my-popo/public/icons/apple-touch-icon.png")
print("Icons created successfully.")
