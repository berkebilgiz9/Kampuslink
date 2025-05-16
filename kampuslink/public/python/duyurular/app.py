from selenium import webdriver
from selenium.webdriver.common.by import By
import time
import json
import threading
import os
import requests

urls = [
    "https://zonguldakteknopark.com.tr/duyurular",
    "https://w3.beun.edu.tr/zbeu-yayinevi/kampusun-sesi.html",
    "https://kariyer.beun.edu.tr/arsiv/tum-duyurular.html"
]


def scrape_site(url):
    options = webdriver.ChromeOptions()
    options.add_argument('--headless')  
    driver = webdriver.Chrome(options=options)

    png_links = []
    id_counter = 1

    try:
        driver.get(url)
        time.sleep(2)

        a_elements = driver.find_elements(By.TAG_NAME, 'a')

        for a in a_elements:
            target = a.get_attribute('target') or a.get_attribute('class')
            href = a.get_attribute('href')
            

            if (target == '_blank' and href and 'kampusun-sesi/' in href) or \
                (target == 'border border-gray-300 rounded-lg overflow-hidden') or \
                (href and 'duyurular/' in href):
                link_data = {
                    "id": id_counter,
                    "link": href
                }
                try:
                    img = a.find_element(By.TAG_NAME, 'img')
                    src = img.get_attribute('src')

                    if src and ('.png' in src or '.jpg' in src or '.jpeg' in src):
                        print(f"[{url}] Bulunan img: {src}, id: {id_counter}")
                        link_data["img"] = src
                except:
                    pass

                png_links.append(link_data)
                id_counter += 1

        
        filename = url.replace('https://', '').replace('http://', '').replace('/', '_') + '.json'

        with open(filename, 'w', encoding='utf-8') as f:
            json.dump(png_links, f, indent=4, ensure_ascii=False)

        print(f"[{url}] {len(png_links)} tane link '{filename}' dosyasına yazıldı.")

    except Exception as e:
        print(f"Hata oluştu {url} - {e}")
    finally:
        driver.quit()

json_file_path = 'zonguldakteknopark.com.tr_duyurular.json'  


download_folder = 'downloaded_images'


if not os.path.exists(download_folder):
    os.makedirs(download_folder)


with open(json_file_path, 'r', encoding='utf-8') as file:
    data = json.load(file)


for item in data:
   
    img_url = item.get('img')
    
    if img_url:
        try:
           
            response = requests.get(img_url)
            
            
            if response.status_code == 200:
                
                img_name = os.path.join(download_folder, img_url.split('/')[-1])
                
                
                with open(img_name, 'wb') as img_file:
                    img_file.write(response.content)
                
                print(f"Resim başarıyla indirildi: {img_name}")
            else:
                print(f"Resim indirilemedi: {img_url}")
        except Exception as e:
            print(f"Resim indirilemedi ({img_url}): {e}")

print("Tüm resimler indirildi.")
while True:
    threads = []

    for url in urls:
        t = threading.Thread(target=scrape_site, args=(url,))
        threads.append(t)
        t.start()


    for t in threads:
        t.join()

    print("Tüm işlemler bitti. 5 dakika sonra tekrar çalışacak...\n")
    
    
    time.sleep(300)
