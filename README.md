[![](https://scdn.rapidapi.com/RapidAPI_banner.png)](https://rapidapi.com/package/Clearbit/functions?utm_source=RapidAPIGitHub_ClearbitFunctions&utm_medium=button&utm_content=RapidAPI_GitHub)

# Clearbit Package
Clearbit
* Domain: [Clearbit](https://clearbit.com/)
* Credentials: apiKey

## How to get credentials: 
0. Go to [Clearbit website](https://clearbit.com/)
1. Register or log in
2. Go to [API page](https://dashboard.clearbit.com/api) to get your apiKey

## Clearbit.findPerson
Lets you retrieve social information associated with an email address, such as a person’s name, location and Twitter handle.

| Field        | Type       | Description
|--------------|------------|----------
| apiKey       | credentials| Api key obtained from Clearbit
| email        | String     | The email to search
| webhookUrl   | String     | A webhook URL that results will be sent to.
| givenName    | String     | First name of person.
| familyName   | String     | Last name of person. If you have this, passing this is strongly recommended to improve match rates.
| ipAddress    | String     | IP address of the person. If you have this, passing this is strongly recommended to improve match rates.
| location     | String     | The city or country where the person resides.
| company      | String     | The name of the person’s employer.
| companyDomain| String     | The domain for the person’s employer.
| linkedin     | String     | The LinkedIn URL for the person.
| twitter      | String     | The Twitter handle for the person.
| facebook     | String     | The Facebook URL for the person.

## Clearbit.subscribeToPersonInfoUpdates
Subscribe to changes in people’s information.  Whenever we receive updates, we’ll post them to the webhook url associated with your account. This will count as an API call.

| Field        | Type       | Description
|--------------|------------|----------
| apiKey       | credentials| Api key obtained from Clearbit
| email        | String     | The email to search
| webhookUrl   | String     | A webhook URL that results will be sent to.
| givenName    | String     | First name of person.
| familyName   | String     | Last name of person. If you have this, passing this is strongly recommended to improve match rates.
| ipAddress    | String     | IP address of the person. If you have this, passing this is strongly recommended to improve match rates.
| location     | String     | The city or country where the person resides.
| company      | String     | The name of the person’s employer.
| companyDomain| String     | The domain for the person’s employer.
| linkedin     | String     | The LinkedIn URL for the person.
| twitter      | String     | The Twitter handle for the person.
| facebook     | String     | The Facebook URL for the person.

## Clearbit.flagPersonInfoAsIncorrect
This endpoint allows you to let us know if a person doesn’t seem quite right. You can flag a person as incorrect and also optionally pass through a correct set of attributes.

| Field           | Type       | Description
|-----------------|------------|----------
| apiKey          | credentials| Api key obtained from Clearbit
| personId        | String     | Id of the person you’d like to flag as inaccurate.
| givenName       | String     | First name of person.
| familyName      | String     | Last name of person. If you have this, passing this is strongly recommended to improve match rates.
| employmentTitle | String     | Company title
| companyDomain   | String     | The domain for the person’s employer.
| linkedinHandle  | String     | LinkedIn url (i.e. /pub/alex-maccaw/78/929/ab5)
| twitterHandle   | String     | Twitter screen name
| facebookHandle  | String     | Facebook ID or screen name
| githubHandle    | String     | GitHub handle
| googleplusHandle| String     | Google Plus handle.
| aboutmeHandle   | String     | about.me handle.

## Clearbit.findCompany
Allows you to look up a company by their domain

| Field      | Type       | Description
|------------|------------|----------
| apiKey     | credentials| Api key obtained from Clearbit
| domain     | String     | The domain to search
| webhookUrl | String     | A webhook URL that results will be sent to.
| companyName| String     | The name of the company.
| linkedin   | String     | The LinkedIn URL for the company.
| twitter    | String     | The Twitter handle for the company.
| facebook   | String     | The Facebook URL for the company.

## Clearbit.flagCompanyInfoAsIncorrect
You can flag a company as incorrect and also optionally pass through a correct set of attributes.

| Field           | Type       | Description
|-----------------|------------|----------
| apiKey          | credentials| Api key obtained from Clearbit
| companyId       | String     | Id of the company you’d like to flag as inaccurate.
| name            | String     | Name of company.
| tags            | Array      | List of market categories the company is involved in
| description     | String     | Description of the company
| raised          | Number     | Total amount raised
| location        | String     | Address of company
| facebookHandle  | String     | Facebook ID or screen name
| crunchbaseHandle| String     | Crunchbase handle
| twitterHandle   | String     | Twitter screen name
| linkedinHandle  | String     | LinkedIn URL (i.e. /pub/alex-maccaw/78/929/ab5)
| employees       | Number     | Amount of employees
| logo            | String     | SRC of company logo
| emailProvider   | Boolean    | Is the domain associated with a free email provider (i.e. Gmail)?
| type            | String     | Type of company (education, government, nonprofit, private, public, personal)

## Clearbit.findInfoByEmail
This endpoint expects an email address, and will return an object containing both the person and company (if found). A call to the combined lookup will only count as one API call.

| Field        | Type       | Description
|--------------|------------|----------
| apiKey       | credentials| Api key obtained from Clearbit
| email        | String     | The email to search
| webhookUrl   | String     | A webhook URL that results will be sent to.
| givenName    | String     | First name of person.
| familyName   | String     | Last name of person. If you have this, passing this is strongly recommended to improve match rates.
| ipAddress    | String     | IP address of the person. If you have this, passing this is strongly recommended to improve match rates.
| location     | String     | The city or country where the person resides.
| company      | String     | The name of the person’s employer.
| companyDomain| String     | The domain for the person’s employer.
| linkedin     | String     | The LinkedIn URL for the person.
| twitter      | String     | The Twitter handle for the person.
| facebook     | String     | The Facebook URL for the person.
| webhookId    | String     | Custom identifier for the webhook request.
| subscribe    | Boolean    | Set to true to subscribe to the changes to the person.

## Clearbit.autocompleteCompany
Lets you auto-complete company names and retreive logo and domain information.

| Field | Type       | Description
|-------|------------|----------
| apiKey| credentials| Api key obtained from Clearbit
| name  | String     | The partial name to search

## Clearbit.findCompanyLogo
Returns link to company logo

| Field    | Type   | Description
|----------|--------|----------
| domain   | String | The domain of the company.
| format   | String | Image format, either "png" or "jpg" (defaults to png)
| size     | Number | Image size: length of longest side in pixels (default is 128)
| greyscale| Boolean| Desaturates image if passed (defaults to false)

