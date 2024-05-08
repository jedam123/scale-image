## Wymagania
Do uruchomienia aplikacji wymagane są następujące aplikacje
- Docker 
- Composer

## Instrukcja instalacji
1. Pobieramy kod lub kopiujemy link do repozytorium (SSH lub HTTPS)
2. Na dysku twardym tworzymy katalog
3. Za pomocą komendy git clone pobieramy kod przykład dla ssh:<br>`git clone git@github.com:jedam123/scale-image.git`
4. Przechodzimy do folderu w którym znajdują się pliki i wykonujemy polecenie `docker compose build`
5. Po pobraniu dockera uruchamiamy komende <br>`composer update`
6. Następnie w celu uruchomienia projektu wykonujemy komende `docker-compose up`
7. W celu sprawdzenia czy aplikacja działa poprawnie otwieramy w przeglądarce adres `localhost:8080` jeśli widzimy domyślną stronę symfony oznacza to, że aplikacja jest poprawnie uruchomiona.

## Generowanie miniaturki i lokalny zapis
1. Do wygenerowania miniaturki korzystamy z komendy `scale:image` po wpisanu `php bin/console list` powinna ona znajdować się na liście.
2. Jeśli chcemy wygenerować miniaturkę i zapisać ją lokalnie wykonujemy poniższy kod:<br>`php bin/console scale:image {FULL_IMAGE_PATH} {DESTINATION_FOLDER}`<br><br>{FULL_IMAGE_PATH} - pełna ścieżka do obrazka przykładowo User/test/test.jpg<br>{DESTINATION_FOLDER} - folder do którego chcemy zapisać miniaturkę przykładowo User/test/thumbnails/