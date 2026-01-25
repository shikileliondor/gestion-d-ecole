<div class="classes-grid" data-classes-grid>
    @forelse ($classes as $class)
        @include('classes.partials.class-card', ['class' => $class])
    @empty
        <div class="empty-state">
            <h3>Aucune classe enregistrée</h3>
            <p>Commencez par créer votre première classe pour affecter élèves et matières.</p>
        </div>
    @endforelse
</div>
