<?php

namespace Database\Factories;

use App\Models\Category;
use App\Models\Contact;
use Illuminate\Database\Eloquent\Factories\Factory;

class ContactFactory extends Factory
{
    protected $model = Contact::class;

    public function definition()
    {
        // tel は数字のみ。10〜11桁を生成。
        $tel = $this->faker->boolean
            ? $this->faker->numerify('0##########')   // 11桁
            : $this->faker->numerify('0#########');   // 10桁

        // gender: 1=男性, 2=女性, 3=その他
        $gender = $this->faker->numberBetween(1, 3);

        // category_id は既存の categories からランダム
        $categoryId = Category::inRandomOrder()->value('id') ?? 1;

        return [
            'last_name'   => $this->faker->lastName,       // 日本語姓
            'first_name'  => $this->faker->firstName,      // 日本語名
            'gender'      => $gender,
            'email'       => $this->faker->unique()->safeEmail,
            'tel'         => $tel,
            'address'     => $this->faker->prefecture . $this->faker->city . $this->faker->streetAddress,
            'building'    => $this->faker->optional()->secondaryAddress,
            'category_id' => $categoryId,
            'detail'      => mb_substr($this->faker->realText(60), 0, 120),
            'created_at'  => now(),
            'updated_at'  => now(),
        ];
    }
}
