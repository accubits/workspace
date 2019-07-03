import { async, ComponentFixture, TestBed } from '@angular/core/testing';

import { PricePreviewComponent } from './price-preview.component';

describe('PricePreviewComponent', () => {
  let component: PricePreviewComponent;
  let fixture: ComponentFixture<PricePreviewComponent>;

  beforeEach(async(() => {
    TestBed.configureTestingModule({
      declarations: [ PricePreviewComponent ]
    })
    .compileComponents();
  }));

  beforeEach(() => {
    fixture = TestBed.createComponent(PricePreviewComponent);
    component = fixture.componentInstance;
    fixture.detectChanges();
  });

  it('should create', () => {
    expect(component).toBeTruthy();
  });
});
