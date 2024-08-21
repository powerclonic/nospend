<x-mail::message>
    # {{ __("Expense paid") }}!

    {{ __("The expense `:name` was paid automatically by our system", [ 'name' => $expense->name]) }}

    <x-mail::table>
        | {{ __("Value") }} | {{ __("Source") }} | | :---- | :---- | |
        {{ Illuminate\Support\Number::currency($expense->value, in: 'BRL', locale: 'pt_BR')  }}
        | {{ $expense->payment_source ?? "Não informada" }} |
    </x-mail::table>

    <x-mail::button :url="$expenseUrl">
        {{ __("Go to expense") }}
    </x-mail::button>

    {{ __("Thank you") }},<br />
    {{ __("NoSpend Team") }}
</x-mail::message>
