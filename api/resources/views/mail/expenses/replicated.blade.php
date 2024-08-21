<x-mail::message>
    # {{ __("Expense replicated") }}!

    {{ __("The expense `:name` was replicated automatically by our system", [ 'name' => $expense->name]) }}

    <x-mail::table>
        | {{ __("Date") }} | {{ __("Value") }} | | :---- | :---- | |
        {{ $expense->due_date->addMonth()->format("d/m/Y") }}
        |{{ Illuminate\Support\Number::currency($expense->value, in: 'BRL', locale: 'pt_BR')  }}
        |
    </x-mail::table>

    <x-mail::button :url="$expenseUrl">
        {{ __("Go to expense") }}
    </x-mail::button>

    {{ __("Thank you") }},<br />
    {{ __("NoSpend Team") }}
</x-mail::message>
