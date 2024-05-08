## Wymagania

Do uruchomienia aplikacji wymagane są następujące aplikacje

- Docker
- Composer

## Instrukcja instalacji

1. Pobieramy kod lub kopiujemy link do repozytorium (SSH lub HTTPS)
2. Na dysku twardym tworzymy katalog
3. Za pomocą komendy git clone pobieramy kod przykład dla ssh:<br>`git clone git@github.com:jedam123/scale-image.git`
4. Po pobraniu dockera uruchamiamy komende <br>`composer update`
5. Przechodzimy do folderu w którym znajdują się pliki i wykonujemy polecenie `docker compose build`
6. Następnie w celu uruchomienia projektu wykonujemy komende `docker-compose up`
7. W celu sprawdzenia czy aplikacja działa poprawnie otwieramy w przeglądarce adres `localhost:8080` jeśli widzimy
   domyślną stronę symfony oznacza to, że aplikacja jest poprawnie uruchomiona.

## Generowanie miniaturki i lokalny zapis

1. Do wygenerowania miniaturki korzystamy z komendy `scale:image` po wpisanu `php bin/console list` powinna ona
   znajdować się na liście.
2. Jeśli chcemy wygenerować miniaturkę i zapisać ją lokalnie wykonujemy poniższy
   kod:<br>`php bin/console scale:image {FULL_IMAGE_PATH} {DESTINATION_FOLDER}`<br><br>{FULL_IMAGE_PATH} - pełna ścieżka
   do obrazka przykładowo User/test/test.jpg<br>{DESTINATION_FOLDER} - folder do którego chcemy zapisać miniaturkę
   przykładowo User/test/thumbnails/

## Generowanie miniaturki i zapis w dropboxie

1. Do wygenerowania miniaturki korzystamy z komendy `scale:image` po wpisanu `php bin/console list` powinna ona
   znajdować się na liście.
2. W przypadku Dropboxa będzie potrzebny nam access token jak go wygenerować można zobaczyć w części "Generowanie access
   token dla dropbox"
3. Jeśli chcemy wygenerować miniaturkę i zapisać ją w dropboxie wykonujemy poniższy
   kod:<br>`php bin/console scale:image {FULL_IMAGE_PATH} {ACCESS_TOKEN} dropbox`<br><br>{FULL_IMAGE_PATH} - pełna
   ścieżka do obrazka przykładowo User/test/test.jpg<br>{ACCESS_TOKEN} - access token wygenerowany na dropboxie

## Generowanie access token dla dropbox

1. Tworzymy konto na dropboxie
2. Pod adresem https://www.dropbox.com/developers/apps tworzymy nową aplikację klikając w przycisk "create app"
3. Wypełniamy formularz i klikamy "create app"
4. W zakładce "Permissions" w sekcji "Files and folders" zaznaczamy "files.content.write" oraz "files.metadata.write" i
   klikamy Submit
5. Wracamy do zakładki "Settings" i w sekcji "OAuth 2" klikamy przycisk "Generate"
6. Kopiujemy access token

## Użyte biblioteki

- intervention/image - wybrałem go ponieważ przyspiesza pracę ze skalowaniem obrazków, podczas wyboru kierowałem się
  popularnością i wsparciem biblioteki, jest to jedna z najpopularniejszych bibliotek