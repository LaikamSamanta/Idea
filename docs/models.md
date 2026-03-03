1. php artisan make:model
2. Idea -  modeļa nosaukums
3. Izvēlies "yes" attiecībā uz factory(dummy jeb fake datu izveidei) un form request(validācijas klase), migration, policy, resource controller.
4. Pēc modeļa izveides atver ''database/migrations'' mapi un atrodi jaunizveidoto migrācijas failu, kurā būs izveidots ''ideas'' tabulas skelets. Pievieno nepieciešamos laukus, piemēram:

```php
    $table->id();
    $table->string('title');
    $table->text('description')->nullable(); // Apraksts var būt tukšs
    $table->string('status')->default('pending'); // Statuss ar noklusējuma vērtību "pending"
    $table->string('image_path')->nullable(); // Attēla ceļš var būt tukšs
    $table->json('links')->default('[]'); // Saite var būt tukša, noklusējuma vērtība ir tukšs JSON masīvs
    $table->timestamps(); // Izveido created_at un updated_at lauku
```

5. Atver ''app/Providers/AuthServiceProvider.php'' un pievieno šo kodu, lai reģistrētu politiku:

```php 
    public function boot(): void
    {
        Model::unguard(); // Izslēdz masīva aizsardzību visiem modeļiem, ļaujot masīva piešķiršanu bez aizsardzības
        Model::shouldBeStrict(); // Ieslēdz stingro režīmu visiem modeļiem, kas nozīmē, ka tiks izmesti izņēmumi, ja tiek piešķirtas neesošas atribūtas vai ja tiek piekļūts neesošām attiecībām
        Model::automaticallyEagerLoadRelationships(); // Automātiski ielādē attiecības, kad tiek piekļūts modeļa atribūtam, kas ir attiecība
    }
```
6. Atver ''app/Models/Idea.php'' un pievieno šo kodu, lai definētu aizpildāmos laukus un attiecības:

```php
    use Illuminate\Database\Eloquent\Model;

     protected $casts = [
        'links' => AsArrayObject::class, // Pārvērš 'links' lauku par masīvu, kad tas tiek piekļūts, un saglabā to kā JSON datubāzē
    ];

```

7. php artisan make:enum
8. IdeaStatus - enum nosaukums
9. Backed enum, string vērtības
10. Pievieno Idea modelim statusa atribūtu, kas izmanto šo enum:

```php
    use App\Enums\IdeaStatus;

    protected $casts = [
        'links' => AsArrayObject::class, // Pārvērš 'links' lauku par masīvu, kad tas tiek piekļūts, un saglabā to kā JSON datubāzē
        'status' => IdeaStatus::class, // Pārvērš 'status' lauku par IdeaStatus enum, kad tas tiek piekļūts, un saglabā to kā string datubāzē
    ];
```
11. Atver ''app/IdeaStatus.php'' un pievieno šo kodu, lai definētu enum vērtības:

```php
    enum IdeaStatus: string
{
    case PENDING = 'pending';
    case IN_PROGRESS = 'in_progress';
    case COMPLETED = 'completed';

    public function label(): string
    {
        return match ($this) {
            self::PENDING => 'Pending',
            self::IN_PROGRESS => 'In Progress',
            self::COMPLETED => 'Completed',
        };
    }
}
```
12. Pārbaudīt ar php artisan tinker, vai viss strādā kā paredzēts:

```bash
php artisan tinker
>>> $idea = new App\Models\Idea();
>>>$idea->status = 'in_progress';
>>> $idea->status; // Atgriezīs IdeaStatus::IN_PROGRESS enum instanci
>>> $idea->status->label(); // Atgriezīs "In Progress"
```

13. Pievieno Idea modelim attiecību ar User modeli, lai saistītu idejas ar lietotājiem:

```php
    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }
```

14. php artisan make:model
15. Step - modeļa nosaukums
16. Izvēlies "yes" attiecībā uz factory(dummy jeb fake datu izveidei) un migration.
17. Atver ''database/migrations'' mapi un atrodi jaunizveidoto migrācijas failu, kurā būs izveidots ''steps'' tabulas skelets.
18. Pievieno nepieciešamos laukus migrācijas failā, piemēram:

```php
    $table->id();
    $table->foreignIdFor(Idea::class)->constrained()->cascadeOnDelete(); // Definē ārējo atslēgu, kas saista soļus ar idejām, un nodrošina, ka soļi tiek izdzēsti, ja tiek izdzēsta saistītā ideja
    $table->string('description'); // Soļa apraksts
    $table->boolean('completed')->default(false); // Norāda, vai solis ir pabeigts
    $table->timestamps();
```

19. php artisan migrate, lai izveidotu datubāzes tabulas ar jaunajiem laukiem un attiecībām:

```bash
php artisan migrate
```

20. Pievieno Idea modelim steps attiecību, lai saistītu idejas ar soļiem:

```php
    public function steps(): HasMany
    {
        return $this->hasMany(Step::class);
    }
```

21. Ej uz Step modeli un pievieno attiecību atpakaļ uz Idea modeli:

```php
    public function idea(): BelongsTo
    {
        return $this->belongsTo(Idea::class);
    }
```

22. User modelim pievieno attiecību ar Idea modeli, lai saistītu lietotājus ar viņu idejām:

```php
    public function ideas(): HasMany
    {
        return $this->hasMany(Idea::class);
    }
```

23. Idea modelim pievieno atribūtus statusam un links, lai nodrošinātu noklusējuma vērtības un pareizu datu tipu:

```php
  
    protected $attributes = [
        'status' => IdeaStatus::PENDING->value, // Noklusējuma vērtība 'status' laukam ir 'pending' no IdeaStatus enum
    ];
```

24. IdeaFactory klasei pievieno definīciju, lai ģenerētu dummy datus ar saistītajiem soļiem un lietotājiem:

```php
    public function definition(): array
    {
        return [
            'user_id' => User::factory(), // Izveido jaunu lietotāju un piešķir tā ID kā user_id
            'title' => fake()->sentence(), // Ģenerē nejaušu virsrakstu
            'description' => fake()->paragraph(), // Ģenerē nejaušu aprakstu
            'links' => [fake()->url()], // Ģenerē masīvu ar nejaušām saitēm
        ];
    }
```

25. Izveido dummy datus ar factory un pārbaudi, vai viss strādā kā paredzēts:

```bash
php artisan tinker
>>> App\Models\Idea::factory()->make(); // Izveidos jaunu Idea instanci ar saistītu lietotāju un soļiem, bet nesaglabās to datubāzē
>>> $idea->status; // Atgriezīs IdeaStatus::PENDING enum instanci, jo tas ir noklusējuma statuss
>>> App\Models\Idea::factory()->create(); // Izveidos un saglabās jaunu Idea instanci ar saistītu lietotāju un soļiem datubāzē
``` 

26. StepFactory klasei pievieno definīciju, lai ģenerētu dummy datus ar saistītajām idejām:

```php
use App\Models\Idea;
    public function definition(): array
    {
        return [
            'idea_id' => Idea::factory(), // Izveido jaunu ideju un piešķir tā ID kā idea_id
            'description' => fake()->sentence(), // Ģenerē nejaušu soļa aprakstu
            'completed' => false, // Sākotnēji norāda, ka solis nav pabeigts
        ];
    }
```

27. Izveido dummy datus Step modelim, lai pārbaudītu attiecības:

```bash
php artisan tinker
>>> $idea = App\Models\Idea::factory()->create(); // Izveido un saglabā jaunu Idea instanci datubāzē
>>> $step = App\Models\Step::factory()->create(['idea_id' => $idea->id]); // Izveido un saglabā jaunu Step instanci, saistot to ar iepriekš izveidoto Idea
>>> $idea->steps; // Atgriezīs kolekciju ar saistītajiem Step objektiem, tostarp nesen izveidoto Step
>>> $step->idea; // Atgriezīs saistīto Idea objektu, kas ir $idea
```
