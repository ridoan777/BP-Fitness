<?php

namespace App\Ai\Agents;

use App\Ai\Tools\MealPreferenceTool;
use App\Models\Workflows\Meals\MealPreference;
use Illuminate\Contracts\JsonSchema\JsonSchema;
use Illuminate\Support\Facades\Log;
use Laravel\Ai\Contracts\{Agent, Conversational, HasTools, Tool};
use Laravel\Ai\Contracts\HasStructuredOutput;
use Laravel\Ai\Attributes\{MaxSteps, MaxTokens, Model, Provider, Temperature, Timeout};
use Laravel\Ai\Attributes\{UseCheapestModel, UseSmartestModel};
use Laravel\Ai\Messages\Message;
use App\Models\Users\User;
use Laravel\Ai\Promptable;

use Stringable;

#[Provider('openai')]
#[Model('gpt-4.1-mini')]
#[MaxSteps(5)]
#[MaxTokens(32000)]
#[Temperature(0.4)]
#[Timeout(240)]

// #[UseCheapestModel]
// #[UseSmartestModel]

class LargeMealGenerationAgent implements Agent, HasStructuredOutput, HasTools
{
    use Promptable;
    public string $model;

    public ?int $startingDay = null;
    public ?int $endingDay = null;
    public ?int $duration;
    public ?array $oldMeals = null;

    public function __construct(
        public User $user,
        public ?MealPreference $TARGET_PREFERENCE,
        public ?int $TARGET_DURATION = null,
        public ?int $START_DAY = null,
        public ?int $END_DAY = null,
        public ?array $generatedMeals
    ) {
        $this->duration = $this?->TARGET_DURATION ? $this?->TARGET_DURATION : ($this->TARGET_PREFERENCE?->duration ?? 3);
        Log::info('Large Agent constructor is hit. Chunk duration=', [$this->duration]);

        if ($this->generatedMeals) {
            $this->oldMeals = $this->generatedMeals ?? null;
        }

        $this->startingDay = $this->START_DAY ?? 1;
        $this->endingDay = $this->END_DAY ?? $this->duration;
    }

    /**
     * Get the instructions that the agent should follow.
     */
    public function instructions(): Stringable|string
    {
        return $this->buildPrompt();
    }

    /**
     * Get the list of messages comprising the conversation so far.
     *
     * @return Message[]
     */
    public function messages(): iterable
    {
        return [];
    }

    /**
     * Get the tools available to the agent.
     *
     * @return Tool[]
     */
    public function tools(): iterable
    {
        return [
            // new MealPreferenceTool(),
        ];
    }

    /**
     * Get the agent's structured output schema definition.
     */

    public function schema(JsonSchema $schema): array
    {
        $days = [];

        for ($i = $this->startingDay; $i <= $this->endingDay; $i++) {
            $days["day-{$i}"] = $schema->object(fn($schema) => [
                'day_number' => $schema->integer()->description("must match the day-{$i}")->required(),

                'meals' => $schema->array()->items(
                    $schema->object(fn($schema) => [
                        'period' => $schema->string()->description('serially breakfast, lunch, dinner')->required(),
                        'title' => $schema->string()->required(),
                        'image_prompt' => $schema->string()->required(),
                        'serving_details' => $schema->string()->required(),

                        'ingredients' => $schema->array()->items($schema->string())->required(),

                        'macro_nutrients' => $schema->object(fn($schema) => [
                            'carb' => $schema->integer()->required(),
                            'fat' => $schema->integer()->required(),
                            'protein' => $schema->integer()->required(),
                            'energy' => $schema->integer()->required(),
                        ])->required(),

                        'grocery_list' => $schema->array()->items(
                            $schema->object(fn($schema) => [
                                'item' => $schema->string()->required(),
                                'qty_type' => $schema->string()->required(),
                                'amount' => $schema->integer()->required(),
                            ])
                        )->required(),
                    ])
                )->required(),
            ])->required();
        }

        return [
            'planning' => $schema->object(fn($schema) => $days)->required(),
        ];
    }
    // --------------------------------------------------

    protected function buildPrompt(): string
    {
        /*
            Due to proprietary secrecy, code that contained business rules were removed
        */
        return "";
    }
}
