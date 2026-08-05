<?php require_once 'includes/header.php'; ?>

<!-- Page Header -->
<div class="page-header text-white text-center" style="background: url('assets/Pinnawala Elephant Orphanage.png') center/cover no-repeat;">
    <div class="position-relative z-index-1">
        <h1 class="display-4 fw-bold brand-font"><i class="fas fa-magic text-accent me-2"></i>Custom Tour Builder</h1>
        <p class="lead opacity-90">Design your personalized Sri Lanka trip in 4 easy steps</p>
    </div>
</div>

<div class="container py-5">
    <div class="row justify-content-center">
        <div class="col-lg-9">
            <div class="card border-0 shadow-lg rounded-4 p-4 p-md-5 bg-white">
                
                <!-- Step Indicator Header -->
                <div class="step-indicator">
                    <div class="step-dot active" id="dot1">1</div>
                    <div class="step-line" id="line1"></div>
                    <div class="step-dot" id="dot2">2</div>
                    <div class="step-line" id="line2"></div>
                    <div class="step-dot" id="dot3">3</div>
                    <div class="step-line" id="line3"></div>
                    <div class="step-dot" id="dot4">4</div>
                </div>

                <form action="index.php?route=store_custom_package" method="POST" id="customBuilderForm">
                    
                    <!-- STEP 1: Select Destination -->
                    <div class="builder-step active" id="step1">
                        <h4 class="fw-bold mb-2 brand-font">Step 1: Where would you like to explore?</h4>
                        <p class="text-muted mb-4">Choose your primary destination in Sri Lanka.</p>
                        
                        <div class="row g-3 mb-4">
                            <?php 
                            $dests = [
                                ['name' => 'Sigiriya & Cultural Triangle', 'img' => 'Sigiriya.png', 'desc' => 'Ancient fortresses & heritage'],
                                ['name' => 'Kandy & Central Highlands', 'img' => 'Temple of the tooth relic Kandy.png', 'desc' => 'Sacred temples & mountains'],
                                ['name' => 'Ella & Tea Country', 'img' => 'Ella.png', 'desc' => 'Hiking, trains & waterfalls'],
                                ['name' => 'Galle & Southern Coast', 'img' => 'Galle fort.png', 'desc' => 'Dutch fort & beaches'],
                                ['name' => 'Koggala & South Beaches', 'img' => 'Koggala.png', 'desc' => 'Stilt fishing & ocean relax'],
                                ['name' => 'Pinnawala & Wildlife', 'img' => 'Pinnawala Elephant Orphanage.png', 'desc' => 'Elephants & nature safaris']
                            ];
                            foreach ($dests as $i => $d):
                            ?>
                                <div class="col-md-4 col-6">
                                    <div class="card h-100 border-2 rounded-4 overflow-hidden position-relative dest-card cursor-pointer" onclick="selectDestination('<?php echo $d['name']; ?>', this)">
                                        <img src="assets/<?php echo $d['img']; ?>" class="card-img-top object-fit-cover" style="height: 120px;" alt="<?php echo $d['name']; ?>">
                                        <div class="card-body p-3 text-center">
                                            <h6 class="fw-bold mb-1 small"><?php echo $d['name']; ?></h6>
                                            <small class="text-muted fs-7"><?php echo $d['desc']; ?></small>
                                        </div>
                                    </div>
                                </div>
                            <?php endforeach; ?>
                        </div>

                        <input type="hidden" name="destination" id="destinationInput" required>

                        <div class="d-flex justify-content-end">
                            <button type="button" class="btn btn-primary rounded-pill px-5 fw-bold" onclick="nextStep(1)">Next: Activities <i class="fas fa-arrow-right ms-2"></i></button>
                        </div>
                    </div>

                    <!-- STEP 2: Select Activities -->
                    <div class="builder-step" id="step2">
                        <h4 class="fw-bold mb-2 brand-font">Step 2: Choose Your Activities</h4>
                        <p class="text-muted mb-4">Select all experiences you want included in your trip.</p>

                        <div class="row g-3 mb-4">
                            <?php 
                            $acts = [
                                ['title' => 'Hiking & Trekking', 'icon' => 'fa-hiking'],
                                ['title' => 'Wildlife Safari', 'icon' => 'fa-hippo'],
                                ['title' => 'Cultural & Temple Visit', 'icon' => 'fa-gopuram'],
                                ['title' => 'Beach & Water Sports', 'icon' => 'fa-umbrella-beach'],
                                ['title' => 'Tea Factory Tour', 'icon' => 'fa-leaf'],
                                ['title' => 'Whale Watching', 'icon' => 'fa-fish']
                            ];
                            foreach ($acts as $a):
                            ?>
                                <div class="col-md-4 col-6">
                                    <div class="activity-card" onclick="toggleActivity(this)">
                                        <input type="checkbox" name="activities[]" value="<?php echo $a['title']; ?>" onchange="calculatePrice()">
                                        <i class="fas <?php echo $a['icon']; ?> d-block mb-2 text-primary"></i>
                                        <h6 class="fw-bold mb-0 small"><?php echo $a['title']; ?></h6>
                                    </div>
                                </div>
                            <?php endforeach; ?>
                        </div>

                        <div class="d-flex justify-content-between">
                            <button type="button" class="btn btn-outline-secondary rounded-pill px-4" onclick="prevStep(2)"><i class="fas fa-arrow-left me-2"></i> Back</button>
                            <button type="button" class="btn btn-primary rounded-pill px-5 fw-bold" onclick="nextStep(2)">Next: Duration <i class="fas fa-arrow-right ms-2"></i></button>
                        </div>
                    </div>

                    <!-- STEP 3: Select Duration & Special Requests -->
                    <div class="builder-step" id="step3">
                        <h4 class="fw-bold mb-2 brand-font">Step 3: Trip Duration & Notes</h4>
                        <p class="text-muted mb-4">Specify how long you plan to travel.</p>

                        <div class="row g-4 mb-4">
                            <div class="col-md-6">
                                <label class="form-label fw-bold">Duration (Days)</label>
                                <input type="number" name="duration" id="durationInput" class="form-control form-control-lg" min="1" max="30" value="3" onchange="calculatePrice()" onkeyup="calculatePrice()" required>
                                <small class="text-muted">Estimated Base Price: Rs. 5,000 / day</small>
                            </div>
                            <div class="col-md-12">
                                <label class="form-label fw-bold">Special Notes or Hotel Preferences (Optional)</label>
                                <textarea name="notes" class="form-control" rows="4" placeholder="Mention budget preference, meal preferences, dietary requirements..."></textarea>
                            </div>
                        </div>

                        <div class="d-flex justify-content-between">
                            <button type="button" class="btn btn-outline-secondary rounded-pill px-4" onclick="prevStep(3)"><i class="fas fa-arrow-left me-2"></i> Back</button>
                            <button type="button" class="btn btn-primary rounded-pill px-5 fw-bold" onclick="nextStep(3)">Next: Summary & Quote <i class="fas fa-arrow-right ms-2"></i></button>
                        </div>
                    </div>

                    <!-- STEP 4: Estimated Price Quote & Submit -->
                    <div class="builder-step" id="step4">
                        <h4 class="fw-bold mb-2 brand-font">Step 4: Review Your Custom Package</h4>
                        <p class="text-muted mb-4">Review your choices and estimated quote before submitting.</p>

                        <div class="bg-light rounded-4 p-4 mb-4 border">
                            <div class="row g-3">
                                <div class="col-md-6">
                                    <small class="text-muted text-uppercase d-block">Destination</small>
                                    <span class="fw-bold text-dark fs-5" id="sumDest">Not selected</span>
                                </div>
                                <div class="col-md-6">
                                    <small class="text-muted text-uppercase d-block">Duration</small>
                                    <span class="fw-bold text-dark fs-5" id="sumDur">3 Days</span>
                                </div>
                                <div class="col-12">
                                    <small class="text-muted text-uppercase d-block mb-1">Selected Activities</small>
                                    <div id="sumActs" class="d-flex flex-wrap gap-2"><span class="badge bg-secondary">None selected</span></div>
                                </div>
                            </div>
                            <hr>
                            <div class="d-flex justify-content-between align-items-center">
                                <div>
                                    <span class="fw-bold text-dark d-block">Estimated Total Price</span>
                                    <small class="text-muted">Subject to admin approval and final confirmation</small>
                                </div>
                                <h3 class="fw-bold text-primary brand-font mb-0" id="estimatedPriceDisplay">Rs. 15,000</h3>
                            </div>
                        </div>

                        <div class="d-flex justify-content-between">
                            <button type="button" class="btn btn-outline-secondary rounded-pill px-4" onclick="prevStep(4)"><i class="fas fa-arrow-left me-2"></i> Back</button>
                            <button type="submit" class="btn btn-accent btn-lg rounded-pill px-5 fw-bold shadow">
                                <i class="fas fa-paper-plane me-2"></i> Submit Tour Request
                            </button>
                        </div>
                    </div>
                </form>

            </div>
        </div>
    </div>
</div>

<script>
let currentStep = 1;

function selectDestination(destName, cardEl) {
    document.querySelectorAll('.dest-card').forEach(c => c.classList.remove('border-primary', 'bg-primary-light'));
    cardEl.classList.add('border-primary', 'bg-primary-light');
    document.getElementById('destinationInput').value = destName;
    document.getElementById('sumDest').innerText = destName;
}

function toggleActivity(cardEl) {
    const cb = cardEl.querySelector('input[type=checkbox]');
    cb.checked = !cb.checked;
    if (cb.checked) {
        cardEl.classList.add('selected');
    } else {
        cardEl.classList.remove('selected');
    }
    calculatePrice();
}

function calculatePrice() {
    const dur = parseInt(document.getElementById('durationInput').value) || 1;
    const checkedActs = document.querySelectorAll('input[name="activities[]"]:checked');
    const actCount = checkedActs.length;

    const price = (dur * 5000) + (actCount * 2000);
    document.getElementById('estimatedPriceDisplay').innerText = 'Rs. ' + price.toLocaleString();

    document.getElementById('sumDur').innerText = dur + ' Day(s)';

    const actsContainer = document.getElementById('sumActs');
    if (actCount === 0) {
        actsContainer.innerHTML = '<span class="badge bg-secondary">None selected</span>';
    } else {
        let html = '';
        checkedActs.forEach(cb => {
            html += `<span class="badge bg-primary rounded-pill px-3 py-2">${cb.value}</span> `;
        });
        actsContainer.innerHTML = html;
    }
}

function nextStep(step) {
    if (step === 1 && !document.getElementById('destinationInput').value) {
        alert('Please select a destination to continue.');
        return;
    }
    document.getElementById('step' + step).classList.remove('active');
    document.getElementById('dot' + step).classList.add('done');
    document.getElementById('line' + step)?.classList.add('done');

    currentStep = step + 1;
    document.getElementById('step' + currentStep).classList.add('active');
    document.getElementById('dot' + currentStep).classList.add('active');
    calculatePrice();
}

function prevStep(step) {
    document.getElementById('step' + step).classList.remove('active');
    document.getElementById('dot' + step).classList.remove('active');

    currentStep = step - 1;
    document.getElementById('step' + currentStep).classList.add('active');
}
</script>

<?php require_once 'includes/footer.php'; ?>
