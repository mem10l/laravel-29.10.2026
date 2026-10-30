<?php

namespace Database\Factories;

use App\Models\Product;
use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Carbon;

/**
 * Produktu testa datu ģenerators.
 *
 * Dati nav pilnīgi nejauši: katram produktam ir īsts nosaukums, atbilstošs
 * apraksts un reāls derīguma laiks dienās (null = bez derīguma termiņa),
 * tāpēc expiration_date, quantity un status ir savstarpēji saskaņoti.
 *
 * @extends Factory<Product>
 */
class ProductFactory extends Factory
{
    protected $model = Product::class;

    /**
     * Produktu katalogs: [nosaukums, apraksts, derīguma laiks dienās vai null].
     *
     * @return array<int, array{0: string, 1: string, 2: int|null}>
     */
    public static function catalog(): array
    {
        return [
            ['Pilngraudu maize 500 g', 'Svaigi cepta maize no pilngraudu kviešu un rudzu miltiem.', 5],
            ['Rudzu maize 700 g', 'Tradicionālā saldskābā rudzu maize ar sēklām.', 7],
            ['Piens 2,5% 1 l', 'Pasterizēts govs piens ar 2,5% tauku saturu.', 10],
            ['Kefīrs 1 l', 'Dabīgs kefīrs bez piedevām, 2,5% tauku.', 14],
            ['Biezpiens 5% 200 g', 'Mīksts biezpiens ar 5% tauku saturu.', 12],
            ['Skābais krējums 20% 400 g', 'Skābais krējums no pasterizēta piena.', 21],
            ['Sviests 82% 200 g', 'Saldkrējuma sviests ar 82% tauku saturu.', 60],
            ['Siers Edamer 250 g', 'Pusciets siers ar maigu, viegli sāļu garšu.', 45],
            ['Olas M izmērs, 10 gab.', 'Brīvās turēšanas vistu olas, izmērs M.', 28],
            ['Vistas fileja 1 kg', 'Atdzesēta vistas krūtiņas fileja bez ādas.', 6],
            ['Cūkgaļas karbonāde 500 g', 'Atdzesēta cūkgaļas karbonāde ar kauliņu.', 7],
            ['Lasis fileja 300 g', 'Atdzesēta Atlantijas laša fileja bez ādas.', 5],
            ['Āboli Auksis 1 kg', 'Latvijā audzēti saldskābi āboli.', 30],
            ['Banāni 1 kg', 'Importēti banāni, ražas pirmā šķira.', 8],
            ['Kartupeļi 2 kg', 'Mazgāti galda kartupeļi, vidēji lieli.', 60],
            ['Burkāni 1 kg', 'Svaigi galda burkāni no vietējiem audzētājiem.', 40],
            ['Tomāti ķekarā 500 g', 'Nogatavojušies tomāti uz zara.', 9],
            ['Gurķi 1 kg', 'Siltumnīcā audzēti svaigi gurķi.', 8],
            ['Auzu pārslas 500 g', 'Ātri vārāmas auzu pārslas bez piedevām.', 365],
            ['Griķi 800 g', 'Tīrīti brūnie griķi, ātri pagatavojami.', 540],
            ['Rīsi basmati 1 kg', 'Garo graudu aromātiskie basmati rīsi.', 720],
            ['Makaroni spageti 500 g', 'Spageti no cietajiem kviešiem.', 730],
            ['Kviešu milti 1 kg', 'Augstākās šķiras universālie kviešu milti.', 365],
            ['Cukurs 1 kg', 'Baltais smalkais cukurs.', null],
            ['Sāls jūras 500 g', 'Smalkā jūras sāls bez piedevām.', null],
            ['Saulespuķu eļļa 1 l', 'Rafinēta saulespuķu eļļa cepšanai.', 540],
            ['Medus ziedu 400 g', 'Latvijas ziedu medus, nepasterizēts.', 730],
            ['Zemeņu ievārījums 350 g', 'Ievārījums no veselām zemenēm, 60% augļu.', 540],
            ['Tēja melnā 25 maisiņi', 'Melnā tēja ar Ceilonas lapām.', 730],
            ['Kafija malta 250 g', 'Vidēji grauzdēta malta Arabica kafija.', 365],
            ['Ābolu sula 1 l', 'Tiešā spiešana ābolu sula bez cukura.', 180],
            ['Minerālūdens 1,5 l', 'Dabīgais negāzētais minerālūdens.', 365],
            ['Šokolāde piena 100 g', 'Piena šokolāde ar 30% kakao.', 270],
            ['Cepumi auzu 300 g', 'Auzu cepumi ar rozīnēm.', 150],
        ];
    }

    /**
     * Nejaušs produkts no kataloga (lieto, ja izsauc Product::factory()->count(n)).
     */
    public function definition(): array
    {
        return $this->fromCatalogItem($this->faker->randomElement(self::catalog()));
    }

    /**
     * Konkrēts produkts no kataloga (lieto seeder, lai nosaukumi neatkārtotos).
     *
     * @param  array{0: string, 1: string, 2: int|null}  $item
     */
    public function fromCatalog(array $item): static
    {
        return $this->state(fn () => $this->fromCatalogItem($item));
    }

    /**
     * @param  array{0: string, 1: string, 2: int|null}  $item
     * @return array<string, mixed>
     */
    private function fromCatalogItem(array $item): array
    {
        [$name, $description, $shelfDays] = $item;

        // ~15% produktu ir beidzies derīguma termiņš, ~10% nav uz noliktavas.
        $expired = $shelfDays !== null && $this->faker->boolean(15);
        $quantity = $this->faker->boolean(10) ? 0 : $this->faker->numberBetween(1, 250);

        $expirationDate = null;
        if ($shelfDays !== null) {
            $expirationDate = $expired
                ? Carbon::today()->subDays($this->faker->numberBetween(1, 30))
                : Carbon::today()->addDays($this->faker->numberBetween(1, $shelfDays));
        }

        return [
            'name' => $name,
            'quantity' => $quantity,
            'description' => $description,
            'expiration_date' => $expirationDate?->toDateString(),
            // Produkts ir aktīvs tikai tad, ja ir noliktavā un nav beidzies termiņš.
            'status' => ($expired || $quantity === 0) ? 'inactive' : 'active',
        ];
    }
}
