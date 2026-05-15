<template>
  <div class="space-y-6">

    <!-- Loading -->
    <div v-if="loading" class="border border-rule px-6 py-16 text-center font-mono text-[11px] text-ink-mute uppercase tracking-widest">
      Loading…
    </div>

    <template v-else>
      <!-- Add chapter button -->
      <div class="flex items-center justify-between">
        <p class="font-mono text-[10px] uppercase tracking-widest text-ink-mute">
          {{ chapters.length }} {{ chapters.length === 1 ? 'Chapter' : 'Chapters' }}
        </p>
        <button
          class="bg-ink text-paper text-xs uppercase tracking-widest px-4 py-2 hover:bg-ink-soft transition-colors"
          @click="toggleAddChapter"
        >
          {{ showAddChapter ? 'Cancel' : '+ Add Chapter' }}
        </button>
      </div>

      <!-- Add chapter inline form -->
      <div v-if="showAddChapter" class="bg-paper border border-rule p-4 space-y-3">
        <p class="font-mono text-[10px] uppercase tracking-widest text-ink-mute">New Chapter</p>
        <div class="grid grid-cols-1 sm:grid-cols-2 gap-3">
          <div>
            <label class="block font-mono text-[10px] uppercase tracking-widest text-ink-mute mb-1">Slug</label>
            <input
              v-model="addChapterForm.slug"
              type="text"
              class="w-full border border-rule bg-paper px-3 py-2 text-sm text-ink focus:outline-none focus:border-accent transition-colors"
              placeholder="e.g. history"
            >
          </div>
          <div>
            <label class="block font-mono text-[10px] uppercase tracking-widest text-ink-mute mb-1">Number</label>
            <input
              v-model="addChapterForm.number"
              type="text"
              class="w-full border border-rule bg-paper px-3 py-2 text-sm text-ink focus:outline-none focus:border-accent transition-colors"
              placeholder="e.g. 01"
            >
          </div>
        </div>
        <div>
          <label class="block font-mono text-[10px] uppercase tracking-widest text-ink-mute mb-1">Title</label>
          <input
            v-model="addChapterForm.title"
            type="text"
            class="w-full border border-rule bg-paper px-3 py-2 text-sm text-ink focus:outline-none focus:border-accent transition-colors"
            placeholder="Chapter title"
          >
        </div>
        <div>
          <label class="block font-mono text-[10px] uppercase tracking-widest text-ink-mute mb-1">Intro</label>
          <textarea
            v-model="addChapterForm.intro"
            rows="3"
            class="w-full border border-rule bg-paper px-3 py-2 text-sm text-ink focus:outline-none focus:border-accent transition-colors resize-none"
            placeholder="Chapter intro (italic Bodoni opener)"
          />
        </div>
        <div class="flex items-center gap-4 pt-1">
          <button
            class="bg-ink text-paper text-xs uppercase tracking-widest px-4 py-2 hover:bg-ink-soft transition-colors disabled:opacity-40"
            :disabled="savingChapter"
            @click="submitAddChapter"
          >
            {{ savingChapter ? 'Saving…' : 'Save Chapter' }}
          </button>
          <button
            class="text-xs uppercase tracking-widest text-ink-mute hover:text-ink transition-colors"
            @click="showAddChapter = false"
          >
            Cancel
          </button>
        </div>
      </div>

      <!-- Empty state -->
      <div v-if="!chapters.length" class="border border-rule px-6 py-16 text-center font-mono text-[11px] text-ink-mute uppercase tracking-widest">
        No chapters yet.
      </div>

      <!-- Chapter accordion list -->
      <div class="border border-rule divide-y divide-rule">
        <div v-for="chapter in chapters" :key="chapter.id">
          <!-- Chapter header -->
          <div
            class="flex items-center gap-3 px-4 py-3 cursor-pointer hover:bg-paper-deep transition-colors select-none"
            @click="toggleChapter(chapter.id)"
          >
            <span class="font-mono text-[10px] uppercase tracking-widest text-accent-deep bg-accent-soft px-2 py-0.5 shrink-0">
              {{ chapter.number }}
            </span>
            <span class="flex-1 text-sm text-ink font-medium">{{ chapter.title }}</span>
            <span class="font-mono text-[10px] uppercase tracking-widest text-ink-mute shrink-0">
              {{ chapter.entries?.length ?? 0 }} {{ (chapter.entries?.length ?? 0) === 1 ? 'entry' : 'entries' }}
            </span>
            <div class="flex items-center gap-2 shrink-0" @click.stop>
              <button
                class="font-mono text-[10px] uppercase tracking-widest text-ink-mute hover:text-ink transition-colors"
                @click="startEditChapter(chapter)"
              >
                Edit
              </button>
              <button
                class="font-mono text-[10px] uppercase tracking-widest text-ink-mute hover:text-red-600 transition-colors"
                @click="deleteChapter(chapter)"
              >
                Delete
              </button>
            </div>
            <Icon
              :name="expandedChapterId === chapter.id ? 'lucide:chevron-up' : 'lucide:chevron-down'"
              size="14"
              class="text-ink-mute shrink-0"
            />
          </div>

          <!-- Expanded chapter body -->
          <div v-if="expandedChapterId === chapter.id" class="border-t border-rule">

            <!-- Chapter edit form (shown when Edit is clicked) -->
            <div v-if="editingChapterId === chapter.id" class="bg-paper border-b border-rule p-4 space-y-3">
              <p class="font-mono text-[10px] uppercase tracking-widest text-ink-mute">Edit Chapter</p>
              <div class="grid grid-cols-1 sm:grid-cols-2 gap-3">
                <div>
                  <label class="block font-mono text-[10px] uppercase tracking-widest text-ink-mute mb-1">Number</label>
                  <input
                    v-model="chapterEditForm.number"
                    type="text"
                    class="w-full border border-rule bg-paper px-3 py-2 text-sm text-ink focus:outline-none focus:border-accent transition-colors"
                  >
                </div>
                <div>
                  <label class="block font-mono text-[10px] uppercase tracking-widest text-ink-mute mb-1">Title</label>
                  <input
                    v-model="chapterEditForm.title"
                    type="text"
                    class="w-full border border-rule bg-paper px-3 py-2 text-sm text-ink focus:outline-none focus:border-accent transition-colors"
                  >
                </div>
              </div>
              <div>
                <label class="block font-mono text-[10px] uppercase tracking-widest text-ink-mute mb-1">Intro</label>
                <textarea
                  v-model="chapterEditForm.intro"
                  rows="3"
                  class="w-full border border-rule bg-paper px-3 py-2 text-sm text-ink focus:outline-none focus:border-accent transition-colors resize-none"
                />
              </div>
              <div class="flex items-center gap-4 pt-1">
                <button
                  class="bg-ink text-paper text-xs uppercase tracking-widest px-4 py-2 hover:bg-ink-soft transition-colors disabled:opacity-40"
                  :disabled="savingChapter"
                  @click="submitEditChapter(chapter)"
                >
                  {{ savingChapter ? 'Saving…' : 'Save' }}
                </button>
                <button
                  class="text-xs uppercase tracking-widest text-ink-mute hover:text-ink transition-colors"
                  @click="editingChapterId = null"
                >
                  Cancel
                </button>
              </div>
            </div>

            <!-- Entry list -->
            <div class="divide-y divide-rule">
              <div v-for="entry in chapter.entries" :key="entry.id">
                <div class="px-4 py-3 space-y-1">
                  <div class="flex items-start justify-between gap-3">
                    <p class="text-sm text-ink font-semibold flex-1">{{ entry.question }}</p>
                    <div class="flex items-center gap-2 shrink-0">
                      <button
                        class="font-mono text-[10px] uppercase tracking-widest text-ink-mute hover:text-ink transition-colors"
                        @click="startEditEntry(entry)"
                      >
                        Edit
                      </button>
                      <button
                        class="font-mono text-[10px] uppercase tracking-widest text-ink-mute hover:text-red-600 transition-colors"
                        @click="deleteEntry(chapter, entry)"
                      >
                        Delete
                      </button>
                    </div>
                  </div>
                  <p class="text-sm text-ink-soft leading-relaxed">{{ entry.answer }}</p>
                </div>
                <!-- Entry edit form -->
                <div v-if="editingEntryId === entry.id" class="bg-paper border-t border-rule p-4 space-y-3">
                  <p class="font-mono text-[10px] uppercase tracking-widest text-ink-mute">Edit Entry</p>
                  <div>
                    <label class="block font-mono text-[10px] uppercase tracking-widest text-ink-mute mb-1">Question</label>
                    <input
                      v-model="entryEditForm.question"
                      type="text"
                      class="w-full border border-rule bg-paper px-3 py-2 text-sm text-ink focus:outline-none focus:border-accent transition-colors"
                    >
                  </div>
                  <div>
                    <label class="block font-mono text-[10px] uppercase tracking-widest text-ink-mute mb-1">Answer</label>
                    <textarea
                      v-model="entryEditForm.answer"
                      rows="4"
                      class="w-full border border-rule bg-paper px-3 py-2 text-sm text-ink focus:outline-none focus:border-accent transition-colors resize-none"
                    />
                  </div>
                  <div class="flex items-center gap-4 pt-1">
                    <button
                      class="bg-ink text-paper text-xs uppercase tracking-widest px-4 py-2 hover:bg-ink-soft transition-colors disabled:opacity-40"
                      :disabled="savingEntry"
                      @click="submitEditEntry(chapter, entry)"
                    >
                      {{ savingEntry ? 'Saving…' : 'Save' }}
                    </button>
                    <button
                      class="text-xs uppercase tracking-widest text-ink-mute hover:text-ink transition-colors"
                      @click="editingEntryId = null"
                    >
                      Cancel
                    </button>
                  </div>
                </div>
              </div>
            </div>

            <!-- Add entry button + form -->
            <div class="px-4 py-3 border-t border-rule">
              <button
                v-if="addingEntryChapterId !== chapter.id"
                class="font-mono text-[10px] uppercase tracking-widest text-ink-mute hover:text-ink transition-colors"
                @click="startAddEntry(chapter.id)"
              >
                + Add Entry
              </button>
              <div v-else class="space-y-3">
                <p class="font-mono text-[10px] uppercase tracking-widest text-ink-mute">New Entry</p>
                <div>
                  <label class="block font-mono text-[10px] uppercase tracking-widest text-ink-mute mb-1">Question</label>
                  <input
                    v-model="addEntryForm.question"
                    type="text"
                    class="w-full border border-rule bg-paper px-3 py-2 text-sm text-ink focus:outline-none focus:border-accent transition-colors"
                    placeholder="Enter question"
                  >
                </div>
                <div>
                  <label class="block font-mono text-[10px] uppercase tracking-widest text-ink-mute mb-1">Answer</label>
                  <textarea
                    v-model="addEntryForm.answer"
                    rows="4"
                    class="w-full border border-rule bg-paper px-3 py-2 text-sm text-ink focus:outline-none focus:border-accent transition-colors resize-none"
                    placeholder="Enter answer"
                  />
                </div>
                <div class="flex items-center gap-4 pt-1">
                  <button
                    class="bg-ink text-paper text-xs uppercase tracking-widest px-4 py-2 hover:bg-ink-soft transition-colors disabled:opacity-40"
                    :disabled="savingEntry"
                    @click="submitAddEntry(chapter)"
                  >
                    {{ savingEntry ? 'Saving…' : 'Save Entry' }}
                  </button>
                  <button
                    class="text-xs uppercase tracking-widest text-ink-mute hover:text-ink transition-colors"
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
