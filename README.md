# Statamic Phone Number Fieldtype

> Statamic Phone Number Fieldtype is a Statamic addon that is everything you need to store and display Phone Numbers on your site.

## Features

- A Phone Number Fieldtype that uses the [International Telephone Input](https://intl-tel-input.com/).
  - **E164 Format**: Phone numbers will be saved in [E164 format](https://www.twilio.com/docs/glossary/what-e164), the international standard.
  - **Country Selection**: Users can select their country and input phone numbers in the national format for ease of use.
  - **Automatic Country Detection**: When users input a phone number in international format, the corresponding country is automatically selected.
  - Customization Options:
    - **Hide Country Select**: Tailor the user interface by hiding the Country select field if not needed.
    - **Initial Country**: Set a default country for a smoother user experience.
    - **Preferred Countries**: Highlight commonly used countries in the Country select dropdown.
    - **Exclude Countries**: Customize the available country options by excluding specific countries.
    - **Only Countries**: Choose to display only certain countries in the Country select dropdown.
- Modifiers
  - **e164_to_national**: Convert E164 formatted phone numbers to the national format.
  - **e164_to_international**: Convert E164 formatted phone numbers to the international format.

## How to Install

You can search for this addon in the `Tools > Addons` section of the Statamic control panel and click **install**, or run the following command from your project root:

```bash
composer require kadegray/statamic-phone-number-fieldtype
```

## How to Use

### Phone Number Fieldtype

Once you include the Phone Number Fieldtype in your blueprint and edit an entry, it will be presented as an [International Telephone Input](https://intl-tel-input.com/) for convenient input and display.

<img src="readme/images/entry_input.png"
    alt="Phone Number Fieldytype in Entry" />

When you open the country select, it will be displayed in the following manner:

<img src="readme/images/entry_input_countries_select.png"
    alt="Phone Number Fieldytype with Countries select open" />

When a user inputs "2015550123" into the field, the fieldtype will automatically format it into E164 format as "+12015550123", where "+1" represents the country code. The phone number will be saved in E164 format within the entry.

<img src="readme/images/entry_input_number_entered.png"
    alt="Phone Number Fieldytype with number entered" />

Upon reloading the page after saving the entry, you will observe that the number is automatically formatted in the national format. This formatting behavior is based on the selected country, allowing for proper handling of both international and national numbers.

<img src="readme/images/entry_input_displayed_in_national_format.png"
    alt="Phone Number Fieldytype with number entered" />

#### Fieldtype Configuration

When you edit the Fieldtype, you have the ability to configure the following options:

- `Show Country Select` - Enable or disable the display of the Country select.
- `Initial Country` - Set an initial Country.
- `Preferred Countries` - Specify preferred Countries to be displayed at the top in the Countries select.
- `Exclude Countries` - Exclude specific Countries from being shown in the Countries select.
- `Only Countries` - Specify the only Countries to be displayed in the Countries select.

### Modifiers

If you have assigned the field handle as `phone_number` or `australia_phone_number`, you can render the original stored value in E164 format using the following code:

```
{{ phone_number }}
{{ australia_phone_number }}
```

**+12015550123**  
**+61412345678**

However, if you prefer to render the number in the national format, you can utilize the `e164_to_national` modifier as follows:

```
{{ phone_number | e164_to_national }}
{{ australia_phone_number | e164_to_national }}
```

**(201) 555-0123**  
**0412 345 678**

Additionally, if you would like to present the number in a more visually appealing international format, you can utilize the `e164_to_international` modifier:

```
{{ phone_number | e164_to_international }}
{{ australia_phone_number | e164_to_international }}
```

**+1 201-555-0123**  
**+61 412 345 678**
