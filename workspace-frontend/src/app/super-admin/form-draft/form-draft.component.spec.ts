import { async, ComponentFixture, TestBed } from '@angular/core/testing';

import { FormDraftComponent } from './form-draft.component';

describe('FormDraftComponent', () => {
  let component: FormDraftComponent;
  let fixture: ComponentFixture<FormDraftComponent>;

  beforeEach(async(() => {
    TestBed.configureTestingModule({
      declarations: [ FormDraftComponent ]
    })
    .compileComponents();
  }));

  beforeEach(() => {
    fixture = TestBed.createComponent(FormDraftComponent);
    component = fixture.componentInstance;
    fixture.detectChanges();
  });

  it('should create', () => {
    expect(component).toBeTruthy();
  });
});
