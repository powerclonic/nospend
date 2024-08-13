<x-mail::message>
    # {{ __("Expense expired") }}!

    {{ __("The expense `:name` has expired", [ 'name' => $expense->name]) }}

    <x-mail::table>
        | {{ __("Value") }} | {{ __("Source") }} | | :---- | :---- | |
        {{ Illuminate\Support\Number::currency($expense->value, in: 'BRL', locale: 'pt_BR')  }}
        | {{ $expense->payment_source ?? "Não informada" }} |
    </x-mail::table>

    {{ __("Thank you") }},<br />
    {{ __("NoSpend Team") }}
</x-mail::message>
