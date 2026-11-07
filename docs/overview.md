# Project Overview

## What this is

The **qijk backend** is the API behind the [qijk widget](https://github.com/JessengBijleng/Qijk-widget). The widget lets leads (visitors of a customer's website) create visualizations of **kitchen wraps**: see what their own kitchen would look like after its cabinets and surfaces are wrapped in a different foil (color, pattern or finish).

The widget is a frontend that customers embed on their websites in an **iframe**. Everything it can't do in the browser happens here: generating the visualization with an AI, checking that the widget is allowed to be used, and keeping track of what leads did.

Read the widget's [docs/overview.md](https://github.com/JessengBijleng/Qijk-widget/blob/main/docs/overview.md) too. Both projects share the roles below and the words defined in [terminology.md](terminology.md).

## Who is involved

| Role | Who they are | What they want |
| --- | --- | --- |
| **Customer** | The business that handles the kitchen wrapping (e.g. a kitchen wrapping company) and pays to embed the widget on its website. | Leads who are convinced and turn into sales, and the details to follow up on them. |
| **Lead** | Someone browsing the customer's website who uses the widget, often a potential client of the customer. | To see what their kitchen could look like before they commit. |
| **Widget** | The frontend in the iframe. This API's only client for now. | Fast, reliable answers it can show to the lead. |
| **AI provider** | The external service that generates the visualizations. | Not a person; a paid dependency we call on the lead's behalf. |

## Responsibilities

This repository is responsible for:

- **Generating visualizations.** Receiving the lead's kitchen photo and chosen wrap, calling the AI that renders the wrapped kitchen, and returning the result to the widget.
- **Authorizing widgets.** Deciding whether a widget request comes from a known customer and an allowed website, and refusing it otherwise. Every AI call costs money, so nobody should be able to use the API for free.
- **Storing lead requests.** Recording what leads did (photos uploaded, wraps chosen, visualizations generated) in the database, scoped to the customer they belong to.
- **Being the source of truth for the photo.** Where the lead's photo is processed, who it is shared with and how long it is kept is decided and enforced here. The widget's privacy copy must match what this backend actually does.

This repository is **not** responsible for:

- The lead's experience. All UI, copy, translations and client-side validation live in the widget.
- The customer's own website.
- Rendering the visualization itself. The AI provider does that; we prepare the input, call it and store the output.

## Constraints from serving an embedded widget

Every change to this project has to respect these constraints:

- **Cross-origin.** The widget runs inside iframes on customers' domains. The API must answer CORS requests, and only for origins that belong to a known customer.
- **No cookies.** Browsers often block or partition third-party cookies in iframes. Don't rely on sessions or cookies for widget requests; authorize with something the widget sends explicitly.
- **Nothing in the widget is secret.** Anything the widget sends (keys, IDs, config) can be read and copied by anyone visiting the host page. Authorization can't depend on a value in the widget alone.
- **Multiple customers.** One backend serves many customers. Every record belongs to a customer, every query is scoped to one, and anything customer-specific (allowed origins, upload limits, wraps, branding) is configuration, not code.
- **Cost and abuse.** AI calls are slow and paid. Rate-limit per customer and per lead, and never call the AI before a request has been authorized and validated.
- **Validate everything again.** The widget validates uploads (JPG, PNG, WEBP and HEIC, up to 15 MB by default) for a good experience, but the API must enforce the same rules itself.
- **Personal data.** A photo of someone's kitchen is personal data. Store as little as needed, for as short as needed, and never log photos or their contents.

## Decisions

- **Tech stack:** Laravel with Pest for tests and Pint for formatting. See [development.md](development.md).
- **Laravel AI SDK** (`laravel/ai`) is the abstraction over AI providers. The provider and model are configuration (`config/kitchen_analysis.php`, env `KITCHEN_ANALYSIS_*`); there is no own provider interface.
- **Kitchen photo analysis** (`POST /api/v1/kitchen-analyses`) is synchronous. It returns the number of cabinets and drawers, an estimated wrappable surface and a description, and stores the analysis together with the photo, so the customer can receive them later. Photos go on a private filesystem disk (`KITCHEN_ANALYSIS_DISK`); use an object-storage disk in production if the host's local filesystem is ephemeral.
- **Photo formats:** the API accepts JPG, PNG and WEBP up to 15 MB. HEIC is converted to JPG in the widget, because not every AI provider accepts HEIC.
- **The widget is the only client** for now. There is no customer-facing dashboard yet.

## Open questions

These aren't decided yet. Don't make assumptions about them; ask, or record the decision here once it's made:

- Hosting.
- Which AI provider and model generate the visualizations (the photo analysis uses OpenAI through the Laravel AI SDK), and whether a request waits for the result or the widget polls for it.
- How a widget authenticates: what identifies a customer, how allowed origins are checked, and whether leads get a short-lived token.
- **What happens to the lead's photo** (purpose, retention, where it's processed, who it's shared with). The widget's privacy copy is a placeholder until this is decided.
- What exactly is stored per request, and for how long.
- How a customer's widget config (locale, upload rules, example images, wraps) is managed and served to the widget.
- Where the wrap catalog comes from and who manages it.
- What happens with a finished visualization (download, share, send to the customer as a lead).
- Whether the backend hands a session over from desktop to phone (the widget's QR code idea).
- Which customer an analysis belongs to. `kitchen_analyses` has no `customer_id` yet, because it depends on how a widget authenticates. Add it, and scope every query to it, as soon as that is decided.
- How long analyses and their photos are kept, and how they are sent to the customer.
- Restricting CORS to the origins of known customers, and rate limiting the AI endpoints per customer and per lead.
