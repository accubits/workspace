import { async, ComponentFixture, TestBed } from '@angular/core/testing';

import { QuestionTypeAnswerComponent } from './question-type-answer.component';

describe('QuestionTypeAnswerComponent', () => {
  let component: QuestionTypeAnswerComponent;
  let fixture: ComponentFixture<QuestionTypeAnswerComponent>;

  beforeEach(async(() => {
    TestBed.configureTestingModule({
      declarations: [ QuestionTypeAnswerComponent ]
    })
    .compileComponents();
  }));

  beforeEach(() => {
    fixture = TestBed.createComponent(QuestionTypeAnswerComponent);
    component = fixture.componentInstance;
    fixture.detectChanges();
  });

  it('should create', () => {
    expect(component).toBeTruthy();
  });
});
