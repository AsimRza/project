@extends('frontend.layouts.default')

@section('title' , __('Əsas səhifə'))

@section('style')
<link rel="stylesheet" type="text/css" href="{{ asset('otherjs/index.css?v=33') }}" />
<script src="{{ asset('otherjs/mapdata.js?v=2') }}"></script>
<script src="{{ asset('otherjs/countrymap.js?v=3') }}"></script>
<link href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css" rel="stylesheet">
@endsection
@section('content')

@php 

$region_id = \Request::get('region');
$region = App\Models\SimpleItem::find($region_id);

if($region)
{
    $offices = App\Models\SimpleItem::where('type', 'notary_offices')->where('A', $region_id)->ordering()->get();
    $offices1 = App\Models\SimpleItem::where('type', 'notary_offices')->where('A', $region_id)->ordering()->paginate(3);
}
    
else
{
    $offices = App\Models\SimpleItem::where('type', 'notary_offices')->ordering()->get();
    $offices1 = App\Models\SimpleItem::where('type', 'notary_offices')->ordering()->paginate(3);
}
  


@endphp
<main class="page_min_height">
    <section class="fullwidth_only_heading">
        <div class="container">
            <div class="content">
                <h1 class="section_heading_lg white">Bakı şəhəri və digər regionlarda fəaliyyət göstərən notariat ofisləri və
                    notariusların siyahısı</h1>

             
            </div>
        </div>
    </section>
    <section class="page_with_breadcrumb">
        <div class="container">
            <div class="content">

                <div class="breadcrumb_container">
                    <div class="breadcrumb_content">
                        <a class="breadcrumb" style="background:transparent" href="{{ route('home') }}">@lang('Ana səhifə')</a>
                        <a class="breadcrumb" style="background:transparent" href="#">Bakı şəhəri və digər regionlarda fəaliyyət göstərən notariat ofisləri və
                            notariusların siyahısı</a>
                    </div>
                    
                    <a class="show_all_btn" href="{{ route('home') }}"><span>@lang('Geriyə qayıt')</span>
                        <div class="show_all_btn__line"></div>
                    </a>

                </div>
                <div class="fullwidth_readmore_content">
                    <div class="container">
                       
                        <div class="row">
                          <div class="col-md-6">
                    
                          
                    
                              <div class="search-area" id="search-area">
                                <h5>Axtarış</h5>
                                <div class="input-group mb-3">
                                    <input type="text" class="form-control" placeholder="Notariat ofisini tapın" aria-label="Search" id="search-input">

                    
                                  <div class="input-group-prepend">
                                    <span class="input-group-text" id="basic-addon1"><img src="https://aznotary.az/src/assets/icons/header/header-search.svg" alt=""></span>
                                  </div>
                                </div>
                    
                                <div class="area-list" style="max-height: 300px; overflow-y: auto;">
                                    <div class="row">
                                        <!-- First Column for Offices -->
                                        <div class="col-6" >
                                            <ul>
                                                @php $current_letter = ''; @endphp
                                                @foreach($offices->take(ceil($offices->count() / 2)) as $office) <!-- First half of the offices -->
                                                    @php
                                                        $first_letter = strtoupper(mb_substr($office->title, 0, 1)); // Get the first letter of the office name
                                                    @endphp
                                    
                                                    @if($first_letter !== $current_letter)
                                                        <li><strong>{{ $first_letter }}</strong></li>
                                                        @php $current_letter = $first_letter; @endphp
                                                    @endif
                                    
                                                    <li onclick="showOfficeDetails('{{ $office->title }}', '{{ $office->address }}', '{{ $office->work_days }}', '{{ $office->phone }}', '{{ $office->email }}')">
                                                        {{ $office->title }}
                                                    </li>
                                                @endforeach
                                            </ul>
                                        </div>
                                    
                                        <!-- Second Column for Offices -->
                                        <div class="col-6" >
                                            <ul>
                                                @php $current_letter = ''; @endphp
                                                @foreach($offices->slice(ceil($offices->count() / 2)) as $office) <!-- Second half of the offices -->
                                                    @php
                                                        $first_letter = strtoupper(mb_substr($office->title, 0, 1)); // Get the first letter of the office name
                                                    @endphp
                                    
                                                    @if($first_letter !== $current_letter)
                                                        <li><strong>{{ $first_letter }}</strong></li>
                                                        @php $current_letter = $first_letter; @endphp
                                                    @endif
                                    
                                                    <li onclick="showOfficeDetails('{{ $office->title }}', '{{ $office->address }}', '{{ $office->work_days }}', '{{ $office->phone }}', '{{ $office->email }}')">
                                                        {{ $office->title }}
                                                    </li>
                                                @endforeach
                                            </ul>
                                        </div>
                                    </div>
                                </div>
                    
                    
                             
                    
                           <!-- Results Area (Hidden by default) -->
<div class="results-area d-none" id="results-area">
    <button class="btn btn-primary mb-3" onclick="goBack()">← Back</button>
    <h5 id="office-name">Office Name</h5>
    <p id="office-address">Office Address</p>
    <p><i class="fas fa-calendar-alt"></i> Work days: <span id="office-work-days">Work Days</span></p>
    <p><i class="fas fa-phone"></i> Phone: <span id="office-phone">Phone Number</span></p>
    <p><i class="fas fa-envelope"></i> Email: <span id="office-email">Email Address</span></p>
    <hr>
</div>
                            </div>
                    
                          </div>
                    
                          <div class="col-md-6">
                            <div id="map"></div>
                          </div>
                        </div>
                      </div>
                </div>
            </div>
        
            <div id="current-location">Sizin ünvan: Loading...</div>


            <div class="container">
                <h1>Notariat ofisini axtar</h1>
                <p>Sizə ən yaxın notariat ofisini tapa bilərsiniz</p>
            
                <div class="search-box">
                  <input type="text" placeholder="Search" id="searchInput">
                </div>
            
                <div class="notary-list" id="results">

                   
            
                </div>
               <!-- <div id="pagination" class="pagination-controls"></div> -->

  
              </div>
        
        </div>
        </section>
</main>



@endsection

@section('js')
<script src="https://maps.googleapis.com/maps/api/js?key=AIzaSyBGMSLsYLEfGdJDMO78hvjZFsPBzuFzfRo"></script>


<script>
   let currentLocation;
let locations = []; // This will hold your notary offices from the backend
const pageSize = 4; // Number of items per page
let currentPage = 1; // Current page number

// Get user's current location automatically on page load
window.onload = function() {
    getCurrentLocation();
};

// Get user's current location
function getCurrentLocation() {
    if (navigator.geolocation) {
        navigator.geolocation.getCurrentPosition(function(position) {
            currentLocation = {
                lat: position.coords.latitude,
                lng: position.coords.longitude
            };
            document.getElementById('current-location').innerHTML = `Your Location: ${currentLocation.lat}, ${currentLocation.lng}`;
            fetchNotaryOffices(); // Fetch notary offices after getting the user's location
        });
    } else {
        alert('Geolocation is not supported by your browser.');
    }
}

// Fetch notary offices from the backend
function fetchNotaryOffices() {
    fetch("{{ route('frontend.offices') }}") 
        .then(response => response.json())
        .then(data => {
            if (data.offices) {
                locations = data.offices.map(office => {
                    const [lat, lng] = extractLatLngFromEmbed(office.embed_url);
                    return {
                        name: office.name,
                        lat: lat,
                        lng: lng
                    };
                });
                sortLocationsByDistance(); // Now sort the locations
                renderPage(currentPage); // Render the first page
                setupPagination(); // Setup pagination controls
            } else {
                alert('No notary offices found.');
            }
        })
        .catch(error => {
            console.error('Error fetching notary offices:', error);
        });
}

// Extract latitude and longitude from the Google Maps embed URL
function extractLatLngFromEmbed(embedUrl) {
    const regex = /!1m17!1m12!1m3!1d[^!]*!2d([^!]+)!3d([^!]+)/;
    const match = embedUrl.match(regex);
    return match ? [parseFloat(match[2]), parseFloat(match[1])] : [null, null]; // Return lat and lng
}

// Calculate distance using Haversine formula
function calculateDistance(lat1, lon1, lat2, lon2) {
    const R = 6371; // Radius of the earth in km
    const dLat = deg2rad(lat2 - lat1);
    const dLon = deg2rad(lon2 - lon1);
    const a =
        Math.sin(dLat / 2) * Math.sin(dLat / 2) +
        Math.cos(deg2rad(lat1)) * Math.cos(deg2rad(lat2)) *
        Math.sin(dLon / 2) * Math.sin(dLon / 2);
    const c = 2 * Math.atan2(Math.sqrt(a), Math.sqrt(1 - a));
    return R * c; // Distance in km
}

function deg2rad(deg) {
    return deg * (Math.PI / 180);
}

// Sort locations by distance from current location
function sortLocationsByDistance() {
    if (currentLocation) {
        locations.forEach(location => {
            location.distance = calculateDistance(currentLocation.lat, currentLocation.lng, location.lat, location.lng);
        });

        // Sort locations by distance
        locations.sort((a, b) => a.distance - b.distance);
    }
}

// Render offices for the current page
function renderPage(page) {
    const resultsContainer = document.getElementById('results');
    resultsContainer.innerHTML = ''; // Clear existing results

    const start = (page - 1) * pageSize; // Calculate start index
    const end = start + pageSize; // Calculate end index
    const paginatedOffices = locations.slice(start, end); // Get items for the current page

    paginatedOffices.forEach(location => {
        const notaryItem = document.createElement('div');
        notaryItem.className = 'notary-item'; // Assign class for styling

        notaryItem.innerHTML = `
            <h2>${location.name}</h2>
            <div class="notary-info row">
                <span class="col"><i class="fa fa-calendar"></i> İş günləri: I, II, III, IV, V, VI (iş saatları: 09:00-18:00)</span>
                <span class="col"><i class="fa fa-phone"></i> Phone: (+99412) 493-45-14</span>
                <span class="col"><i class="fa fa-envelope"></i> Email: <a href="mailto:bakunotary1@gmail.com">bakunotary1@gmail.com</a></span>
            </div>
            <div><strong>Distance:</strong> ${location.distance.toFixed(2)} km</div>
        `;

        resultsContainer.appendChild(notaryItem); // Append the created item to the results container
    });
}

// Setup pagination controls
function setupPagination() {
    const paginationContainer = document.getElementById('pagination');
    paginationContainer.innerHTML = ''; // Clear previous pagination controls

    const totalPages = Math.ceil(locations.length / pageSize); // Calculate total pages

    for (let i = 1; i <= totalPages; i++) {
        const pageButton = document.createElement('button');
        pageButton.textContent = i;
        pageButton.className = 'pagination-button'; // Assign class for styling
        pageButton.onclick = function() {
            currentPage = i; // Update current page
            renderPage(currentPage); // Render the new page
        };
        paginationContainer.appendChild(pageButton); // Append the button to the pagination container
    }
}


    </script>
    
<script>
    document.getElementById('search-input').addEventListener('keyup', function() {
        // Get the search query
        let query = this.value.toLowerCase();

        // Select all list items (regions or offices)
        let listItems = document.querySelectorAll('.area-list ul li');

        // Loop through all list items and filter based on the search query
        listItems.forEach(function(item) {
            // Get the text content of the list item and convert it to lowercase
            let text = item.textContent.toLowerCase();

            // Check if the item contains the search query
            if (text.includes(query)) {
                // If it matches, show the item
                item.style.display = '';
            } else {
                // If it doesn't match, hide the item
                item.style.display = 'none';
            }
        });
    });

    
    document.getElementById("searchInput").addEventListener("input", function() {
      const filter = this.value.toLowerCase();
      const notaryItems = document.querySelectorAll(".notary-item");

      notaryItems.forEach(function(item) {
        const text = item.textContent.toLowerCase();
        if (text.includes(filter)) {
          item.style.display = "";
        } else {
          item.style.display = "none";
        }
      });
    });

     // Function to show the office details
     function showOfficeDetails(name, address, workDays, phone, email) {
        // Update the office details in the result area
        document.getElementById('office-name').textContent = name;
        document.getElementById('office-address').textContent = address;
        document.getElementById('office-work-days').textContent = workDays;
        document.getElementById('office-phone').textContent = phone;
        document.getElementById('office-email').textContent = email;

        // Hide the office list and show the result area
        document.querySelector('.area-list').classList.add('d-none');
        document.getElementById('results-area').classList.remove('d-none');
    }

    // Function to go back to the office list
    function goBack() {
        // Hide the result area and show the office list again
        document.getElementById('results-area').classList.add('d-none');
        document.querySelector('.area-list').classList.remove('d-none');
    }
</script>
@endsection