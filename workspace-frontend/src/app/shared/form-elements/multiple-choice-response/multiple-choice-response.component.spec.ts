import { async, ComponentFixture, TestBed } from '@angular/core/testing';

import { MultipleChoiceResponseComponent } from './multiple-choice-response.component';

describe('MultipleChoiceResponseComponent', () => {
  let component: MultipleChoiceResponseComponent;
  let fixture: ComponentFixture<MultipleChoiceResponseComponent>;

  beforeEach(async(() => {
    TestBed.configureTestingModule({
      declarations: [ MultipleChoiceResponseComponent ]
    })
    .compileComponents();
  }));

  beforeEach(() => {
    fixture = TestBed.createComponent(MultipleChoiceResponseComponent);
    component = fixture.componentInstance;
    fixture.detectChanges();
  });

  it('should create', () => {
    expect(component).toBeTruthy();
  });
});
