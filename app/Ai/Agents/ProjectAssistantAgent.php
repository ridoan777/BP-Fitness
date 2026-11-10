<?php

namespace App\Ai\Agents;

use App\Ai\Tools\{ListProjectTool, ListProjectToolToUser};
use Laravel\Ai\Contracts\{Agent, Conversational, HasTools, Tool};
use Laravel\Ai\Attributes\{MaxSteps, MaxTokens, Model, Provider, Temperature, Timeout};
use Laravel\Ai\Attributes\{UseCheapestModel, UseSmartestModel};
use Laravel\Ai\Messages\Message;
use App\Models\Users\User;
use Laravel\Ai\Promptable;

use Stringable;

// #[Provider('openai')]
#[Model('gpt-4.1')]
#[MaxSteps(10)]
#[MaxTokens(4096)]
#[Temperature(0.7)]
#[Timeout(120)]


// #[UseCheapestModel]
// #[UseSmartestModel]

class ProjectAssistantAgent implements Agent, Conversational, HasTools
{
    use Promptable;

    public function __construct(public User $user)
    {
    }

    /**
     * Get the instructions that the agent should follow.
     */
    public function instructions(): Stringable|string
    {
        return <<<'PROMPT'
            PROMPT;
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
            new ListProjectTool,
            new ListProjectToolToUser,
        ];
    }
}
