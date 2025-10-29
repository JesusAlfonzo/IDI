import { defineCollection, z } from 'astro:content';

const examenesCollection = defineCollection({
  type: 'content',
  schema: z.object({
    title: z.string().max(80, 'El título es demasiado largo. Máximo 80 caracteres.'),
    description: z.string().max(160, 'La descripción es demasiado larga. Máximo 160 caracteres.'),
    pubDate: z.date().optional(),
    // AÑADIDA LA CATEGORÍA 'Inmunogenética'
    category: z.enum(['General', 'Retrovirus', 'Citometría', 'Inmunodiagnóstico', 'Inmunogenética']).default('General'), 
    tipoExamen: z.string().max(50, 'El tipo de examen es demasiado largo.'),
    price: z.number().positive('El precio debe ser un número positivo.'),
  }),
});

export const collections = {
  examenes: examenesCollection,
};