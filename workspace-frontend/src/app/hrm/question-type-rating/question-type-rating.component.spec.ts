import { async, ComponentFixture, TestBed } from '@angular/core/testing';

import { QuestionTypeRatingComponent } from './question-type-rating.component';

describe('QuestionTypeRatingComponent', () => {
  let component: QuestionTypeRatingComponent;
  let fixture: ComponentFixture<QuestionTypeRatingComponent>;

  beforeEach(async(() => {
    TestBed.configureTestingModule({
      declarations: [ QuestionTypeRatingComponent ]
    })
    .compileComponents();
  }));

  beforeEach(() => {
    fixture = TestBed.createComponent(QuestionTypeRatingComponent);
    component = fixture.componentInstance;
    fixture.detectChanges();
  });

  it('should create', () => {
    expect(component).toBeTruthy();
  });
});
