<x-mail::message>
# New Message from Quick Resume Tailor

You have received a new contact submission from your website.

**Designation (Name):** {{ $data['name'] }}  
**Comlink (Email):** {{ $data['email'] }}

**Transmission Details:**  
{{ $data['message'] }}

Thanks,<br>
{{ config('app.name') }}
</x-mail::message>
