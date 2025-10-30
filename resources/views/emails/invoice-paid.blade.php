<p>Hi {{ $user->first_name ?? 'there' }},</p>
<p>Thanks for your payment. Your invoice <strong>{{ $invoice['number'] }}</strong> is paid.</p>
<p>
    Total: <strong>{{ number_format($invoice['total']/100, 2) }} {{ $invoice['currency'] }}</strong><br>
    <a href="{{ $invoice['hosted_url'] }}">View invoice online</a> |
    <a href="{{ $invoice['pdf_url'] }}">Download PDF</a>
</p>
@if(!empty($invoice['lines']))
    <p>Items:</p>
    <ul>
        @foreach($invoice['lines'] as $line)
            <li>
                {{ $line['description'] ?? ($line['product'] ?? 'Item') }}
                — {{ $line['quantity'] ?? 1 }} ×
                {{ number_format($line['amount']/100, 2) }} {{ $line['currency'] }}
            </li>
        @endforeach
    </ul>
@endif
<p>— HumanOp Billing</p>

