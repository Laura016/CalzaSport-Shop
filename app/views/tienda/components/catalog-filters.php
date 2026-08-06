<section class="catalog-filters">

    <div class="search-box">

        <i class="fa-solid fa-magnifying-glass"></i>

        <input
            type="text"
            id="buscarProducto"
            placeholder="Buscar por nombre o referencia...">

    </div>

    <div class="filter-group">

        <h3>Categorías</h3>

        <div class="category-chips">

            <button class="chip active" data-category="">
                Todos
            </button>

            <button class="chip" data-category="Hombre">
                Hombre
            </button>

            <button class="chip" data-category="Mujer">
                Mujer
            </button>

        </div>

    </div>

    <div class="filters-row">

        <div>

            <label>Talla</label>

            <select id="talla">

                <option value="">Todas</option>

                <option>36</option>
                <option>37</option>
                <option>38</option>
                <option>39</option>
                <option>40</option>
                <option>41</option>
                <option>42</option>
                <option>43</option>

            </select>

        </div>

        <div>

            <label>Ordenar</label>

            <select id="orden">

                <option value="recientes">
                    Más recientes
                </option>

                <option value="precio_asc">
                    Precio: menor a mayor
                </option>

                <option value="precio_desc">
                    Precio: mayor a menor
                </option>

                <option value="nombre">
                    Nombre A-Z
                </option>

            </select>

        </div>

    </div>

</section>