FROM node:20-alpine

RUN apk add --no-cache bash curl

WORKDIR /app

COPY app/package*.json ./

RUN chown -R node:node /app

USER node
RUN npm install
COPY --chown=node:node app/ .

EXPOSE 5173

CMD ["npm", "run", "dev", "--", "--host"]