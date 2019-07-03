import { async, ComponentFixture, TestBed } from '@angular/core/testing';

import { MultipleChoicesComponent } from './multiple-choices.component';

describe('MultipleChoicesComponent', () => {
  let component: MultipleChoicesComponent;
  let fixture: ComponentFixture<MultipleChoicesComponent>;

  beforeEach(async(() => {
    TestBed.configureTestingModule({
      declarations: [ MultipleChoicesComponent ]
    })
    .compileComponents();
  }));

  beforeEach(() => {
    fixture = TestBed.createComponent(MultipleChoicesComponent);
    component = fixture.componentInstance;
    fixture.detectChanges();
  });

  it('should create', () => {
    expect(component).toBeTruthy();
  });
});
