<?php

namespace App\Contracts\Subscribers;

use App\Contracts\EntitySchema;
use \Illuminate\Http\Request;
use Illuminate\Support\Carbon;

class DefaultSubscriberSchema extends EntitySchema
{
    /**
     * Create an instance of subscriber schema.
     * 
     * @param ?string $id
     * @param string $name
     * @param string $nationalId
     * @param Carbon $subscription_expire_date
     */
    private function __construct(
        public readonly string $name,
        public readonly string $national_id,
        public readonly Carbon $subscription_expire_date,
        public readonly ?string $id,
    ) {
        //
    }

    /**
     * Create an instance of subscriber schema from arguments.
     * 
     * @param string $name
     * @param string $nationalId
     * @param Carbon $subscription_expire_date
     * @param ?string $id
     * @return DefaultSubscriberSchema
     */
    public static function create(
        string $name,
        string $nationalId,
        string $subscription_expire_date,
        ?string $id = null,
    ): self {
        $subscription_expire_date = $subscription_expire_date ? Carbon::parse($subscription_expire_date) : Carbon::now();

        return new self(
            $name ?? "",
            $nationalId ?? "",
            $subscription_expire_date,
            $id,
        );
    }

    /**
     * Create an instance of subscriber schema from request.
     * 
     * @param Request $request
     * @return DefaultSubscriberSchema
     */
    public static function fromRequest(Request $request): self
    {
        $subscription_expire_date = $request->input('subscription_expire_date') ?
            Carbon::parse((string) $request->input('subscription_expire_date')) :
            Carbon::now()->addDays(config('library.subscribers.subscription_minimum_length'));

        return new self(
            $request->input('name'),
            $request->input('national_id'),
            $subscription_expire_date,
            $request->input('id'),
        );
    }

    /**
     * Convert the subscriber schema to array.
     * 
     * @return array
     */
    public function toArray(): array
    {
        return [
            'id' => $this->id,
            'name' => $this->name,
            'national_id' => $this->national_id,
            'subscription_expire_date' => $this->subscription_expire_date,
        ];
    }
}