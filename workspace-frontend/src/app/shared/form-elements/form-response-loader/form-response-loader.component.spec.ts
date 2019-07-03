import { async, ComponentFixture, TestBed } from '@angular/core/testing';

import { FormResponseLoaderComponent } from './form-response-loader.component';

describe('FormResponseLoaderComponent', () => {
  let component: FormResponseLoaderComponent;
  let fixture: ComponentFixture<FormResponseLoaderComponent>;

  beforeEach(async(() => {
    TestBed.configureTestingModule({
      declarations: [ FormResponseLoaderComponent ]
    })
    .compileComponents();
  }));

  beforeEach(() => {
    fixture = TestBed.createComponent(FormResponseLoaderComponent);
    component = fixture.componentInstance;
    fixture.detectChanges();
  });

  it('should create', () => {
    expect(component).toBeTruthy();
  });
});
