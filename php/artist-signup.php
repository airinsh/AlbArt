<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Artist Sign Up - AlbArt</title>
    <link rel="stylesheet" href="../css/artist-signup.css">
</head>
<body>
<div class="signup-section">
    <h1>Artist</h1>
    <form class="signup-form" id="artistSignupForm" enctype="multipart/form-data">

        <div class="form-row">
            <label>Emri</label>
            <input type="text" name="name" id="name" required>
        </div>

        <div class="form-row">
            <label>Mbiemri</label>
            <input type="text" name="surname" id="surname" required>
        </div>

        <div class="form-row">
            <label>Email</label>
            <input type="email" name="email" id="email" required>
        </div>

        <div class="form-row">
            <label>Password</label>
            <input type="password" name="password" id="password" required>
        </div>

        <div class="form-row">
            <label>Description</label>
            <textarea name="description" id="description" rows="4" placeholder="Tell us about yourself" required></textarea>
        </div>

        <div class="form-row">
            <label>Certifikime (PDF)</label>
            <input type="file" name="certification" id="certification" accept=".pdf" required>
        </div>

        <div class="form-row checkbox-row">
            <input type="checkbox" id="terms" required>
            <span>I agree to  <a href="#" onclick="openTerms();">AlbArt Terms and Conditions</a></span>

        </div>

        <p id="message"></p>
        <button type="submit" class="btn">Continue</button>
    </form>
</div>

<script src="../php/Artist-signup.js"></script>


<div id="termsModal" class="terms-modal">
    <div class="terms-box">
        <h2>AlbArt – Terms & Conditions</h2>

        <div class="terms-content">

            <h3>1. Pranimi i kushteve</h3>
            <p>
                Duke u regjistruar dhe duke përdorur platformën AlbArt, ju pranoni këto
                Terma dhe Kushte. Nëse nuk pajtoheni me ndonjë pjesë të tyre, ju lutemi
                mos përdorni shërbimet e AlbArt.
            </p>

            <h3>2. Regjistrimi i përdoruesit</h3>
            <p>
                Për t’u regjistruar si artist në AlbArt, ju duhet të ofroni informacione
                të sakta, të plota dhe të përditësuara. Çdo informacion i rremë ose
                mashtrues mund të çojë në pezullimin ose fshirjen e llogarisë suaj.
            </p>

            <h3>3. Verifikimi dhe certifikimet</h3>
            <p>
                Artistët janë të detyruar të ngarkojnë dokumente ose certifikime të
                vlefshme, kur kjo kërkohet. AlbArt rezervon të drejtën të verifikojë këto
                dokumente dhe të refuzojë regjistrimin në rast mospërputhjeje.
            </p>

            <h3>4. Përgjegjësitë e përdoruesit</h3>
            <p>
                Ju jeni përgjegjës për ruajtjen e konfidencialitetit të llogarisë suaj dhe
                për çdo aktivitet që kryhet përmes saj. AlbArt nuk mban përgjegjësi për
                përdorim të paautorizuar të llogarisë suaj.
            </p>

            <h3>5. Përmbajtja dhe të drejtat e autorit</h3>
            <p>
                Të gjitha veprat artistike dhe materialet e ngarkuara mbeten pronë e
                autorit. Duke i publikuar ato në AlbArt, ju i jepni platformës të drejtën
                t’i shfaqë dhe promovojë ato për qëllime funksionale dhe promovuese.
            </p>

            <h3>6. Sjellja e ndaluar</h3>
            <p>
                Ndalohet publikimi i përmbajtjes fyese, diskriminuese, të paligjshme ose
                që shkel të drejtat e palëve të treta. AlbArt rezervon të drejtën të
                heqë çdo përmbajtje që shkel këto rregulla.
            </p>

            <h3>7. Privatësia dhe të dhënat personale</h3>
            <p>
                Të dhënat personale përpunohen në përputhje me ligjet në fuqi për
                mbrojtjen e të dhënave. AlbArt angazhohet të ruajë konfidencialitetin e
                informacionit tuaj.
            </p>

            <h3>8. Kufizimi i përgjegjësisë</h3>
            <p>
                AlbArt nuk garanton funksionim të pandërprerë ose pa gabime teknike.
                Platforma nuk mban përgjegjësi për dëme direkte ose indirekte që mund të
                lindin nga përdorimi i saj.
            </p>

            <h3>9. Ndryshimet në terma</h3>
            <p>
                AlbArt rezervon të drejtën të ndryshojë këto Terma dhe Kushte në çdo
                kohë. Përdorimi i vazhdueshëm i platformës nënkupton pranimin e
                ndryshimeve.
            </p>

            <h3>10. Ligji i zbatueshëm</h3>
            <p>
                Këto Terma dhe Kushte rregullohen dhe interpretohen në përputhje me
                ligjet në fuqi të Republikës së Shqipërisë.
            </p>

        </div>


        <button type="button" class="btn back-btn" onclick="closeTerms()">Back</button>
    </div>
</div>


</body>
</html>
