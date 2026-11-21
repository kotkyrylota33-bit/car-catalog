import getpass
import requests
from bs4 import BeautifulSoup

BASE_URL = "http://127.0.0.1:8000"
EMAIL = input("Email адміна [admin@example.com]: ").strip() or "admin@example.com"
PASSWORD = getpass.getpass("Пароль адміна: ")

session = requests.Session()

# 1. Отримуємо CSRF-токен зі сторінки /login
login_page = session.get(f"{BASE_URL}/login")
soup = BeautifulSoup(login_page.text, "html.parser")
csrf_input = soup.find("input", {"name": "_csrf_token"})
csrf_token = csrf_input["value"] if csrf_input else ""

# 2. Авторизуємося з заголовками Origin/Referer (потрібно для Symfony 8 stateless CSRF)
login_resp = session.post(
    f"{BASE_URL}/login",
    data={
        "_username": EMAIL,
        "_password": PASSWORD,
        "_csrf_token": csrf_token,
    },
    headers={
        "Origin": BASE_URL,
        "Referer": f"{BASE_URL}/login",
    },
)
print("Login status:", login_resp.status_code, "-> URL:", login_resp.url)

# 3. Отримуємо список авто через JSON API
cars_resp = session.get(f"{BASE_URL}/api/cars")
print("GET /api/cars:", cars_resp.status_code)
for car in cars_resp.json():
    print(f" - [{car['id']}] {car['brand']} {car['model']} ({car['year']}) — ${car['price']}")

# 4. Додаємо нове авто через захищений POST /api/cars (тільки ROLE_ADMIN)
new_car = {
    "brand_id": 1,
    "model": "Octavia A5",
    "year": 2012,
    "engine": "1.4 TSI",
    "price": 7500,
    "description": "Додано через Python-клієнт",
}
create_resp = session.post(f"{BASE_URL}/api/cars", json=new_car)
print("POST /api/cars:", create_resp.status_code)
try:
    print("Response:", create_resp.json())
except Exception:
    print("Response (not JSON):", create_resp.url, create_resp.text[:200])