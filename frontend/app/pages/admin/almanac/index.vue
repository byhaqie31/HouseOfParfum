<template>
  <div class="space-y-6">

    <AdminPageHeader title="Almanac" sub="Chapters and questions, the perfumery primer." />

    <!-- Loading -->
    <div
      v-if="loading"
      class="rounded-card border px-6 py-16 text-center fm uppercase"
      style="border-color: var(--color-rule); background: var(--color-surface); font-size: 11px; letter-spacing: 0.14em; color: var(--color-ink-mute);"
    >
      Loading…
    </div>

    <template v-else>
      <!-- Add chapter button -->
      <div class="flex items-center justify-between">
        <p class="fm uppercase" style="font-size: 10px; letter-spacing: 0.12em; color: var(--color-ink-mute);">
          {{ chapters.length }} {{ chapters.length === 1 ? 'Chapter' : 'Chapters' }}
        </p>
        <button
          v-if="!showAddChapter"
          class="alm-btn-primary"
          @click="toggleAddChapter"
        >
          + Add Chapter
        </button>
        <button
          v-else
          class="alm-btn-ghost"
          @click="toggleAddChapter"
        >
          Cancel
        </button>
      </div>

      <!-- Add chapter inline form -->
      <div
        v-if="showAddChapter"
        class="rounded-card border p-5 space-y-3"
        style="border-color: var(--color-rule); background: var(--color-surface);"
      >
        <p class="fm uppercase" style="font-size: 10px; letter-spacing: 0.12em; color: var(--color-ink-mute);">New Chapter</p>
        <div class="grid grid-cols-1 sm:grid-cols-2 gap-3">
          <div>
            <label class="alm-label">Slug</label>
            <input
              v-model="addChapterForm.slug"
              type="text"
              class="alm-input fb"
              placeholder="e.g. history"
            >
          </div>
          <div>
            <label class="alm-label">Number</label>
            <input
              v-model="addChapterForm.number"
              type="text"
              class="alm-input fb"
              placeholder="e.g. 01"
            >
          </div>
        </div>
        <div>
          <label class="alm-label">Title</label>
          <input
            v-model="addChapterForm.title"
            type="text"
            class="alm-input fb"
            placeholder="Chapter title"
          >
        </div>
        <div>
          <label class="alm-label">Intro</label>
          <textarea
            v-model="addChapterForm.intro"
            rows="3"
            class="alm-input fb resize-none"
            placeholder="Chapter intro (italic Bodoni opener)"
          />
        </div>
        <div class="flex items-center gap-3 pt-1">
          <button
            class="alm-btn-primary disabled:opacity-40"
            :disabled="savingChapter"
            @click="submitAddChapter"
          >
            {{ savingChapter ? 'Saving…' : 'Save Chapter' }}
          </button>
          <button
            class="alm-btn-ghost"
            @click="showAddChapter = false"
          >
            Cancel
          </button>
        </div>
      </div>

      <!-- Empty state -->
      <div
        v-if="!chapters.length"
        class="rounded-card border px-6 py-16 text-center fm uppercase"
        style="border-color: var(--color-rule); background: var(--color-surface); font-size: 11px; letter-spacing: 0.14em; color: var(--color-ink-mute);"
      >
        No chapters yet.
      </div>

      <!-- Chapter accordion list -->
      <div class="flex flex-col gap-3">
        <div
          v-for="chapter in chapters"
          :key="chapter.id"
          class="rounded-card border overflow-hidden"
          style="border-color: var(--color-rule); background: var(--color-surface);"
        >
          <!-- Chapter header -->
          <div
            class="alm-row flex items-center gap-3 px-4 py-3.5 cursor-pointer select-none"
            @click="toggleChapter(chapter.id)"
          >
            <span
              class="fm uppercase shrink-0 rounded-full"
              style="font-size: 10px; letter-spacing: 0.1em; color: var(--color-accent-deep); background: var(--color-accent-soft); padding: 4px 10px;"
            >
              {{ chapter.number }}
            </span>
            <span class="fd flex-1" style="font-size: 15px; color: var(--color-ink);">{{ chapter.title }}</span>
            <span class="fm uppercase shrink-0" style="font-size: 10px; letter-spacing: 0.1em; color: var(--color-ink-mute);">
              {{ chapter.entries?.length ?? 0 }} {{ (chapter.entries?.length ?? 0) === 1 ? 'entry' : 'entries' }}
            </span>
            <div class="flex items-center gap-3 shrink-0" @click.stop>
              <button
                class="alm-btn-link"
                @click="startEditChapter(chapter)"
              >
                Edit
              </button>
              <button
                class="alm-btn-danger"
                @click="deleteChapter(chapter)"
              >
                Delete
              </button>
            </div>
            <Icon
              :name="expandedChapterId === chapter.id ? 'lucide:chevron-up' : 'lucide:chevron-down'"
              size="14"
              class="shrink-0"
              style="color: var(--color-ink-mute);"
            />
          </div>

          <!-- Expanded chapter body -->
          <div v-if="expandedChapterId === chapter.id" style="border-top: 1px solid var(--color-rule);">

            <!-- Chapter edit form (shown when Edit is clicked) -->
            <div
              v-if="editingChapterId === chapter.id"
              class="p-5 space-y-3"
              style="border-bottom: 1px solid var(--color-rule); background: var(--color-surface-2);"
            >
              <p class="fm uppercase" style="font-size: 10px; letter-spacing: 0.12em; color: var(--color-ink-mute);">Edit Chapter</p>
              <div class="grid grid-cols-1 sm:grid-cols-2 gap-3">
                <div>
                  <label class="alm-label">Number</label>
                  <input
                    v-model="chapterEditForm.number"
                    type="text"
                    class="alm-input fb"
                  >
                </div>
                <div>
                  <label class="alm-label">Title</label>
                  <input
                    v-model="chapterEditForm.title"
                    type="text"
                    class="alm-input fb"
                  >
                </div>
              </div>
              <div>
                <label class="alm-label">Intro</label>
                <textarea
                  v-model="chapterEditForm.intro"
                  rows="3"
                  class="alm-input fb resize-none"
                />
              </div>
              <div class="flex items-center gap-3 pt-1">
                <button
                  class="alm-btn-primary disabled:opacity-40"
                  :disabled="savingChapter"
                  @click="submitEditChapter(chapter)"
                >
                  {{ savingChapter ? 'Saving…' : 'Save' }}
                </button>
                <button
                  class="alm-btn-ghost"
                  @click="editingChapterId = null"
                >
                  Cancel
                </button>
              </div>
            </div>

            <!-- Entry list -->
            <div>
              <div
                v-for="(entry, i) in chapter.entries"
                :key="entry.id"
                :style="i ? 'border-top: 1px solid var(--color-rule);' : ''"
              >
                <div class="px-4 py-3.5 space-y-1">
                  <div class="flex items-start justify-between gap-3">
                    <p class="fb flex-1" style="font-size: 14px; font-weight: 600; color: var(--color-ink);">{{ entry.question }}</p>
                    <div class="flex items-center gap-3 shrink-0">
                      <button
                        class="alm-btn-link"
                        @click="startEditEntry(entry)"
                      >
                        Edit
                      </button>
                      <button
                        class="alm-btn-danger"
                        @click="deleteEntry(chapter, entry)"
                      >
                        Delete
                      </button>
                    </div>
                  </div>
                  <p class="fb leading-relaxed" style="font-size: 14px; color: var(--color-ink-soft);">{{ entry.answer }}</p>
                </div>
                <!-- Entry edit form -->
                <div
                  v-if="editingEntryId === entry.id"
                  class="p-5 space-y-3"
                  style="border-top: 1px solid var(--color-rule); background: var(--color-surface-2);"
                >
                  <p class="fm uppercase" style="font-size: 10px; letter-spacing: 0.12em; color: var(--color-ink-mute);">Edit Entry</p>
                  <div>
                    <label class="alm-label">Question</label>
                    <input
                      v-model="entryEditForm.question"
                      type="text"
                      class="alm-input fb"
                    >
                  </div>
                  <div>
                    <label class="alm-label">Answer</label>
                    <textarea
                      v-model="entryEditForm.answer"
                      rows="4"
                      class="alm-input fb resize-none"
                    />
                  </div>
                  <div class="flex items-center gap-3 pt-1">
                    <button
                      class="alm-btn-primary disabled:opacity-40"
                      :disabled="savingEntry"
                      @click="submitEditEntry(chapter, entry)"
                    >
                      {{ savingEntry ? 'Saving…' : 'Save' }}
                    </button>
                    <button
                      class="alm-btn-ghost"
                      @click="editingEntryId = null"
                    >
                      Cancel
                    </button>
                  </div>
                </div>
              </div>
            </div>

            <!-- Add entry button + form -->
            <div class="px-4 py-3.5" style="border-top: 1px solid var(--color-rule);">
              <button
                v-if="addingEntryChapterId !== chapter.id"
                class="alm-btn-link"
                @click="startAddEntry(chapter.id)"
              >
                + Add Entry
              </button>
              <div v-else class="space-y-3">
                <p class="fm uppercase" style="font-size: 10px; letter-spacing: 0.12em; color: var(--color-ink-mute);">New Entry</p>
                <div>
                  <label class="alm-label">Question</label>
                  <input
                    v-model="addEntryForm.question"
                    type="text"
                    class="alm-input fb"
                    placeholder="Enter question"
                  >
                </div>
                <div>
                  <label class="alm-label">Answer</label>
                  <textarea
                    v-model="addEntryForm.answer"
                    rows="4"
                    class="alm-input fb resize-none"
                    placeholder="Enter answer"
                  />
                </div>
                <div class="flex items-center gap-3 pt-1">
                  <button
                    class="alm-btn-primary disabled:opacity-40"
                    :disabled="savingEntry"
                    @click="submitAddEntry(chapter)"
                  >
                    {{ savingEntry ? 'Saving…' : 'Save Entry' }}
                  </button>
                  <button
                    class="alm-btn-ghost"
                    @click="addingEntryChapterId = null"
                  >
                    Cancel
                  </button>
                </div>
              </div>
            </div>

          </div>
        </div>
      </div>
    </template>

  </div>
</template>

<script setup lang="ts">
definePageMeta({ layout: 'admin', middleware: 'admin' })

const api = useApi()

interface AlmanacEntry {
  id: number
  question: string
  answer: string
}

interface AlmanacChapter {
  id: number
  slug: string
  number: string
  title: string
  intro: string
  entries: AlmanacEntry[]
}

// State
const chapters = ref<AlmanacChapter[]>([])
const loading = ref(true)

// Accordion
const expandedChapterId = ref<number | null>(null)

// Add chapter form
const showAddChapter = ref(false)
const savingChapter = ref(false)
const addChapterForm = ref({ slug: '', number: '', title: '', intro: '' })

// Edit chapter form
const editingChapterId = ref<number | null>(null)
const chapterEditForm = ref({ number: '', title: '', intro: '' })

// Add entry form
const addingEntryChapterId = ref<number | null>(null)
const savingEntry = ref(false)
const addEntryForm = ref({ question: '', answer: '' })

// Edit entry form
const editingEntryId = ref<number | null>(null)
const entryEditForm = ref({ question: '', answer: '' })

const toggleChapter = (id: number) => {
  expandedChapterId.value = expandedChapterId.value === id ? null : id
  // close any open forms when collapsing
  if (expandedChapterId.value !== id) {
    editingChapterId.value = null
    addingEntryChapterId.value = null
    editingEntryId.value = null
  }
}

// Chapter CRUD
const toggleAddChapter = () => {
  showAddChapter.value = !showAddChapter.value
  if (showAddChapter.value) {
    addChapterForm.value = { slug: '', number: '', title: '', intro: '' }
  }
}

const submitAddChapter = async () => {
  savingChapter.value = true
  try {
    const created = await api.post('/admin/almanac', addChapterForm.value)
    chapters.value.push({ ...created, entries: created.entries ?? [] })
    showAddChapter.value = false
    addChapterForm.value = { slug: '', number: '', title: '', intro: '' }
  }
  finally {
    savingChapter.value = false
  }
}

const startEditChapter = (chapter: AlmanacChapter) => {
  expandedChapterId.value = chapter.id
  chapterEditForm.value = { number: chapter.number, title: chapter.title, intro: chapter.intro }
  editingChapterId.value = chapter.id
}

const submitEditChapter = async (chapter: AlmanacChapter) => {
  savingChapter.value = true
  try {
    const updated = await api.patch(`/admin/almanac/${chapter.id}`, chapterEditForm.value)
    const idx = chapters.value.findIndex(c => c.id === chapter.id)
    if (idx !== -1) {
      chapters.value[idx] = { ...chapters.value[idx], ...updated }
    }
    editingChapterId.value = null
  }
  finally {
    savingChapter.value = false
  }
}

const deleteChapter = async (chapter: AlmanacChapter) => {
  if (!window.confirm(`Delete chapter "${chapter.title}"? This will also remove all its entries.`)) return
  await api.delete(`/admin/almanac/${chapter.id}`)
  chapters.value = chapters.value.filter(c => c.id !== chapter.id)
  if (expandedChapterId.value === chapter.id) expandedChapterId.value = null
}

// Entry CRUD
const startAddEntry = (chapterId: number) => {
  addingEntryChapterId.value = chapterId
  addEntryForm.value = { question: '', answer: '' }
}

const submitAddEntry = async (chapter: AlmanacChapter) => {
  savingEntry.value = true
  try {
    const created = await api.post(`/admin/almanac/${chapter.id}/entries`, addEntryForm.value)
    const ch = chapters.value.find(c => c.id === chapter.id)
    if (ch) ch.entries.push(created)
    addingEntryChapterId.value = null
    addEntryForm.value = { question: '', answer: '' }
  }
  finally {
    savingEntry.value = false
  }
}

const startEditEntry = (entry: AlmanacEntry) => {
  editingEntryId.value = entry.id
  entryEditForm.value = { question: entry.question, answer: entry.answer }
}

const submitEditEntry = async (chapter: AlmanacChapter, entry: AlmanacEntry) => {
  savingEntry.value = true
  try {
    const updated = await api.patch(`/admin/almanac/entries/${entry.id}`, entryEditForm.value)
    const ch = chapters.value.find(c => c.id === chapter.id)
    if (ch) {
      const idx = ch.entries.findIndex(e => e.id === entry.id)
      if (idx !== -1) ch.entries[idx] = { ...ch.entries[idx], ...updated }
    }
    editingEntryId.value = null
  }
  finally {
    savingEntry.value = false
  }
}

const deleteEntry = async (chapter: AlmanacChapter, entry: AlmanacEntry) => {
  if (!window.confirm(`Delete entry "${entry.question}"?`)) return
  await api.delete(`/admin/almanac/entries/${entry.id}`)
  const ch = chapters.value.find(c => c.id === chapter.id)
  if (ch) ch.entries = ch.entries.filter(e => e.id !== entry.id)
}

// Load
onMounted(async () => {
  const res = await api.get('/admin/almanac')
  chapters.value = Array.isArray(res) ? res : (res.data ?? [])
  loading.value = false
})
</script>

<style scoped>
/* Field label — tabular uppercase, mirrors the admin curate drawer */
.alm-label {
  display: block;
  margin-bottom: 6px;
  font-family: var(--font-data);
  font-weight: 700;
  font-variant-numeric: tabular-nums;
  text-transform: uppercase;
  font-size: 10px;
  letter-spacing: 0.14em;
  color: var(--color-ink-mute);
}

/* Inputs / textareas — mirror .reg-input / .admin-input */
.alm-input {
  width: 100%;
  background: var(--color-surface);
  border: 1px solid var(--color-rule);
  border-radius: var(--radius-field);
  padding: 10px 13px;
  font-size: 13px;
  color: var(--color-ink);
  outline: none;
  transition: border-color 0.14s;
}
.alm-input::placeholder { color: var(--color-ink-mute); }
.alm-input:focus { border-color: var(--color-accent); }

/* Primary / save buttons — accent fill, rounded */
.alm-btn-primary {
  border-radius: var(--radius-field);
  padding: 10px 18px;
  font-family: var(--font-data);
  font-weight: 700;
  text-transform: uppercase;
  font-size: 11px;
  letter-spacing: 0.12em;
  background: var(--color-accent);
  color: var(--color-canvas);
  transition: background-color 0.14s, opacity 0.14s;
}
.alm-btn-primary:hover:not(:disabled) { background: var(--color-accent-deep); }

/* Cancel — bordered text button */
.alm-btn-ghost {
  border-radius: var(--radius-field);
  padding: 10px 18px;
  border: 1px solid var(--color-rule);
  background: transparent;
  font-family: var(--font-data);
  font-weight: 700;
  text-transform: uppercase;
  font-size: 11px;
  letter-spacing: 0.12em;
  color: var(--color-ink-soft);
  transition: color 0.14s, border-color 0.14s;
}
.alm-btn-ghost:hover { color: var(--color-ink); border-color: var(--color-ink-mute); }

/* Inline text actions (Edit / + Add Entry) */
.alm-btn-link {
  font-family: var(--font-data);
  font-weight: 700;
  text-transform: uppercase;
  font-size: 10px;
  letter-spacing: 0.1em;
  color: var(--color-ink-mute);
  transition: color 0.14s;
}
.alm-btn-link:hover { color: var(--color-ink); }

/* Destructive text action */
.alm-btn-danger {
  font-family: var(--font-data);
  font-weight: 700;
  text-transform: uppercase;
  font-size: 10px;
  letter-spacing: 0.1em;
  color: var(--color-ink-mute);
  transition: color 0.14s;
}
.alm-btn-danger:hover { color: oklch(0.6 0.15 25); }

/* Chapter header hover */
.alm-row { transition: background-color 0.12s; }
.alm-row:hover { background: var(--color-surface-2); }
</style>
