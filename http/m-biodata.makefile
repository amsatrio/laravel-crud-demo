base_url=http://localhost:8000

id=2
module=m-biodata

sort_param := [{"id":"fullname", "desc":false}]
encoded_sort := $(shell echo -n '$(sort_param)' | jq -sRr @uri)
filter_param := [{"id":"fullname", "value":"m", "matchMode":"CONTAINS"}]
encoded_filter := $(shell echo -n '$(filter_param)' | jq -sRr @uri)

pagination:
	curl -X GET "${base_url}/api/${module}?page=0&size=5" | jq
pagination-sort:
	curl -X GET "${base_url}/api/${module}?page=0&size=5&sort=$(encoded_sort)" | jq
pagination-filter:
	curl -X GET "${base_url}/api/${module}?page=0&size=5&filter=$(encoded_filter)" | jq

get:
	curl -X GET "${base_url}/api/${module}/${id}"
post:
	curl -X POST "${base_url}/api/${module}" \
	-H "Content-Type: application/json" \
	-d '{"fullname":"name created","mobile_phone":"087653424242","is_delete":false}' | jq
put:
	curl -X PUT "${base_url}/api/${module}/${id}" \
	-H "Content-Type: application/json" \
	-d '{"fullname":"name updated","mobile_phone":"087653424242","is_delete":false}' | jq
delete:
	curl -X DELETE "${base_url}/api/${module}/${id}"
